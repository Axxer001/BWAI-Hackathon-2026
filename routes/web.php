<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\WasteAssessmentController;
use App\Http\Controllers\ReportIssueController;
use App\Http\Controllers\CollectionSessionController;

// Landing Page
Route::get('/', function () {
    return view('index');
});

// Authentication Group with both path URL prefix AND route name prefix
// Authentication Group
Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    
    // Login Routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Registration Routes
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    // ADD THIS LINE: Logout Route
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
});

// Dashboard Routing Group (Protected by Auth)
Route::middleware('auth')->group(function () {
    
    // User: Eco-Points View
    Route::get('/dashboard/points', function () {
        // Points to: resources/views/dashboard/partials/user/my-eco-points.blade.php
        return view('dashboard.partials.user.my-eco-points'); 
    })->name('dashboard.points');

    Route::get('/dashboard/points-map', function () {
        return view('dashboard.partials.user.my-collection-point'); 
    })->name('dashboard.points-map');

    // 💡 Add the rest of your user routes below as you build the views:
    
    
    Route::get('/dashboard/ai-scanner', [WasteAssessmentController::class, 'showScanner'])->name('dashboard.ai-scanner');

    
    Route::get('/dashboard/report-missed', function () {
        return view('dashboard.partials.user.my-report-missed-pickup'); 
    });

    
    Route::get('/dashboard/report-violation', function () {
        return view('dashboard.partials.user.my-report-illegal-dumping'); 
    });
    

});
// API routes for map data (fetching and saving)
Route::get('/api/garbage-points', [MapController::class, 'getPoints']);
Route::post('/api/garbage-points', [MapController::class, 'storePoint']);

Route::get('/dashboard', function () {
    $role = strtolower(auth()->user()->role);

    if ($role === 'admin') {
        return redirect()->route('admin.overview');
    }

    if ($role === 'collector') {
        return redirect()->route('dashboard.active-session');
    }

    if ($role === 'barangay') {
        $barangayId = auth()->user()->barangay_id;

        // Active trucks count
        $activeTrucksCount = \App\Models\Truck::where('barangay_id', $barangayId)
            ->where('is_active', true)
            ->count();

        // Total points collected today
        // Count scans created today in the user's barangay * 5 points
        $scansTodayCount = \App\Models\WasteScan::whereDate('created_at', today())
            ->whereHas('user', function ($query) use ($barangayId) {
                $query->where('barangay_id', $barangayId);
            })
            ->count();
        $totalPointsToday = $scansTodayCount * 5;

        // Unresolved reports count (missed collections and violations)
        $unresolvedMissedCount = \App\Models\MissedCollectionReport::where('status', 'pending')
            ->whereHas('collectionPoint', function ($query) use ($barangayId) {
                $query->where('barangay_id', $barangayId);
            })
            ->count();

        $unresolvedViolationsCount = \App\Models\ViolationReport::where('status', 'pending')
            ->where('barangay_id', $barangayId)
            ->count();

        $unresolvedReportsCount = $unresolvedMissedCount + $unresolvedViolationsCount;

        // Get recent scans
        $recentScans = \App\Models\WasteScan::whereHas('user', function ($query) use ($barangayId) {
                $query->where('barangay_id', $barangayId);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get collection points
        $collectionPoints = \App\Models\CollectionPoint::where('barangay_id', $barangayId)->get();

        return view('dashboard.partials.barangay.dashboard', compact(
            'activeTrucksCount',
            'totalPointsToday',
            'unresolvedReportsCount',
            'unresolvedMissedCount',
            'unresolvedViolationsCount',
            'recentScans',
            'collectionPoints'
        ));
    }

    // Default to resident points
    return redirect()->route('dashboard.points');
})->middleware('auth')->name('dashboard');

// ─── BARANGAY ROUTES ─────────────────────────────────────────────────

    Route::get('/dashboard/schedules', function () {
        $barangayId = auth()->user()->barangay_id;
        $schedules = \App\Models\CollectionSchedule::where('barangay_id', $barangayId)->get();
        return view('dashboard.partials.barangay.schedules', compact('schedules')); 
    })->name('dashboard.schedules');

    Route::post('/dashboard/schedules', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'collection_time' => 'required',
            'frequency' => 'required|in:weekly,bi-weekly,daily',
        ]);

        \App\Models\CollectionSchedule::create([
            'barangay_id' => auth()->user()->barangay_id,
            'day_of_week' => $request->day_of_week,
            'collection_time' => $request->collection_time,
            'frequency' => $request->frequency,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Collection schedule added successfully!');
    })->name('dashboard.schedules.store');

    Route::post('/dashboard/schedules/{id}/toggle', function ($id) {
        $sched = \App\Models\CollectionSchedule::findOrFail($id);
        $sched->update(['is_active' => !$sched->is_active]);
        return redirect()->back()->with('success', 'Schedule updated successfully!');
    })->name('dashboard.schedules.toggle');

    Route::delete('/dashboard/schedules/{id}', function ($id) {
        \App\Models\CollectionSchedule::destroy($id);
        return redirect()->back()->with('success', 'Schedule deleted successfully!');
    })->name('dashboard.schedules.delete');

    Route::get('/dashboard/fleet', function () {
        $barangayId = auth()->user()->barangay_id;
        $trucks = \App\Models\Truck::where('barangay_id', $barangayId)->get();
        $collectors = \App\Models\User::where('role', 'collector')
            ->where('barangay_id', $barangayId)
            ->get();
        return view('dashboard.partials.barangay.fleet', compact('trucks', 'collectors')); 
    })->name('dashboard.fleet');

    Route::post('/dashboard/fleet/trucks', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'plate_number' => 'required|string|max:50',
            'capacity_tons' => 'required|numeric|min:0.1',
        ]);

        \App\Models\Truck::create([
            'plate_number' => $request->plate_number,
            'capacity_tons' => $request->capacity_tons,
            'barangay_id' => auth()->user()->barangay_id,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Truck added to fleet successfully!');
    })->name('dashboard.fleet.trucks.store');

    Route::post('/dashboard/fleet/trucks/{id}/toggle', function ($id) {
        $truck = \App\Models\Truck::findOrFail($id);
        $truck->update(['is_active' => !$truck->is_active]);
        return redirect()->back()->with('success', 'Truck status updated successfully!');
    })->name('dashboard.fleet.trucks.toggle');

    Route::post('/dashboard/fleet/collectors', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        \App\Models\User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'role' => 'collector',
            'barangay_id' => auth()->user()->barangay_id,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Collector account created successfully!');
    })->name('dashboard.fleet.collectors.store');

    Route::get('/dashboard/points-manage', function () {
        return view('dashboard.partials.barangay.points-manage'); 
    })->name('dashboard.points-manage');

    Route::get('/api/barangay/live-tracking', function () {
        $barangayId = auth()->user()->barangay_id;
        $sessions = \App\Models\CollectionSession::where('status', 'active')
            ->where('barangay_id', $barangayId)
            ->with(['collector', 'sessionPoints.garbagePoint'])
            ->get();
        return response()->json($sessions);
    })->name('api.barangay.live-tracking');

    Route::get('/dashboard/tickets-missed', function () {
        $barangayId = auth()->user()->barangay_id;
        $reports = \App\Models\MissedCollectionReport::whereHas('collectionPoint', function ($query) use ($barangayId) {
            $query->where('barangay_id', $barangayId);
        })->with(['reporter', 'collectionPoint'])->orderBy('created_at', 'desc')->get();
        return view('dashboard.partials.barangay.tickets-missed', compact('reports')); 
    })->name('dashboard.tickets-missed');

    Route::post('/dashboard/tickets-missed/{id}/action', function (\Illuminate\Http\Request $request, $id) {
        $report = \App\Models\MissedCollectionReport::findOrFail($id);
        $request->validate(['status' => 'required|in:resolved,invalid']);
        $report->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Missed collection ticket updated successfully!');
    })->name('dashboard.tickets-missed.action');

    Route::get('/dashboard/tickets-violations', function () {
        $barangayId = auth()->user()->barangay_id;
        $reports = \App\Models\ViolationReport::where('barangay_id', $barangayId)
            ->with('reporter')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('dashboard.partials.barangay.tickets-violations', compact('reports')); 
    })->name('dashboard.tickets-violations');

    Route::post('/dashboard/tickets-violations/{id}/action', function (\Illuminate\Http\Request $request, $id) {
        $report = \App\Models\ViolationReport::findOrFail($id);
        $request->validate(['status' => 'required|in:resolved,investigating,dismissed']);
        $report->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Violation ticket updated successfully!');
    })->name('dashboard.tickets-violations.action');

// ─── ADMIN ROUTES ────────────────────────────────────────────────────

    Route::get('/admin/overview', function () {
        $totalBarangays = \App\Models\Barangay::count();
        $totalEcoPoints = \DB::table('eco_points_transactions')->sum('points');
        $totalWaste = \App\Models\CollectionReport::sum('completed_points') * 0.1;
        $totalTrucks = \App\Models\Truck::count();
        $barangays = \App\Models\Barangay::withCount(['users', 'trucks', 'collectionPoints'])->get();
        
        return view('dashboard.partials.admin.overview', compact('totalBarangays', 'totalEcoPoints', 'totalWaste', 'totalTrucks', 'barangays'));
    })->name('admin.overview');

    Route::get('/admin/users', function (\Illuminate\Http\Request $request) {
        $search = $request->query('search');
        $role = $request->query('role');
        
        $query = \App\Models\User::with('barangay');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($role) {
            $query->where('role', $role);
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        $barangays = \App\Models\Barangay::all();
        
        return view('dashboard.partials.admin.users', compact('users', 'barangays'));
    })->name('admin.users');

    Route::post('/admin/users/{id}/toggle-status', function ($id) {
        $user = \App\Models\User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot suspend your own account!');
        }
        $user->is_active = !$user->is_active;
        $user->save();
        return redirect()->back()->with('success', 'User account status toggled successfully!');
    })->name('admin.users.toggle');

    Route::post('/admin/users/{id}/update', function (\Illuminate\Http\Request $request, $id) {
        $user = \App\Models\User::findOrFail($id);
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required|in:admin,barangay,collector,user',
            'barangay_id' => 'nullable|exists:barangays,id',
        ]);
        
        $user->update($request->only('full_name', 'email', 'role', 'barangay_id'));
        return redirect()->back()->with('success', 'User updated successfully!');
    })->name('admin.users.update');

    Route::get('/admin/barangays', function () {
        $barangays = \App\Models\Barangay::withCount(['users', 'trucks', 'collectionPoints'])->get();
        return view('dashboard.partials.admin.barangays', compact('barangays')); 
    })->name('admin.barangays');

    Route::post('/admin/barangays', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'admin_name' => 'nullable|string|max:255',
            'admin_email' => 'nullable|email|unique:users,email',
            'admin_password' => 'nullable|min:6',
        ]);

        \DB::transaction(function () use ($request) {
            $barangay = \App\Models\Barangay::create([
                'name' => $request->name,
                'district' => $request->district,
            ]);

            if ($request->filled('admin_email')) {
                \App\Models\User::create([
                    'full_name' => $request->admin_name ?? ($request->name . ' Admin'),
                    'email' => $request->admin_email,
                    'password' => bcrypt($request->admin_password),
                    'role' => 'barangay',
                    'barangay_id' => $barangay->id,
                ]);
            }
        });

        return redirect()->back()->with('success', 'Barangay onboarded successfully!');
    })->name('admin.barangays.store');

    Route::get('/admin/analytics', function () {
        $totalEcoPoints = \DB::table('eco_points_transactions')->sum('points');
        $totalWaste = \App\Models\CollectionReport::sum('completed_points') * 0.1;
        $totalViolations = \App\Models\ViolationReport::where('status', 'pending')->count();
        $barangays = \App\Models\Barangay::all()->map(function($b) {
            $sessions = \App\Models\CollectionSession::where('barangay_id', $b->id)->pluck('id');
            $reports = \App\Models\CollectionReport::whereIn('session_id', $sessions)->get();
            $b->completed_count = $reports->count();
            $b->waste_collected = $reports->sum('completed_points') * 0.1;
            $b->points_distributed = $reports->sum('total_points');
            $b->avg_completion = $b->completed_count > 0 
                ? round($reports->avg(function($r) { return $r->completionRate(); }), 2) 
                : 0;
            return $b;
        });
        return view('dashboard.partials.admin.analytics', compact('totalEcoPoints', 'totalWaste', 'totalViolations', 'barangays')); 
    })->name('admin.analytics');

    Route::get('/admin/analytics/download', function () {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=barangay_efficiency_report.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $barangays = \App\Models\Barangay::all();

        $callback = function() use($barangays) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Barangay Name', 'Completed Sessions', 'Total Notified Residents', 'Total Eco-Points Distributed', 'Avg Completion Rate (%)']);

            foreach ($barangays as $barangay) {
                $sessions = \App\Models\CollectionSession::where('barangay_id', $barangay->id)->pluck('id');
                $reports = \App\Models\CollectionReport::whereIn('session_id', $sessions)->get();
                
                $completedCount = $reports->count();
                $totalNotified = $reports->sum('total_notified_users');
                $totalPoints = $reports->sum('total_points');
                
                $avgCompletion = $completedCount > 0 
                    ? round($reports->avg(function($r) { return $r->completionRate(); }), 2) 
                    : 0;

                fputcsv($file, [
                    $barangay->name,
                    $completedCount,
                    $totalNotified,
                    $totalPoints,
                    $avgCompletion
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    })->name('admin.analytics.download');

    Route::get('/admin/system-settings', function () {
        return view('dashboard.partials.admin.system-settings'); 
    })->name('admin.system-settings');

// ─── COLLECTOR ROUTES ────────────────────────────────────────────────

    Route::get('/dashboard/active-session', [CollectionSessionController::class, 'activeSession'])->middleware('auth')->name('dashboard.active-session');
    Route::post('/dashboard/active-session/start', [CollectionSessionController::class, 'startSession'])->middleware('auth')->name('dashboard.start-session');

    Route::get('/dashboard/route-map', [CollectionSessionController::class, 'routeMap'])->middleware('auth')->name('dashboard.route-map');
    Route::post('/dashboard/route-map/{id}/start', [CollectionSessionController::class, 'startRoute'])->middleware('auth')->name('dashboard.start-route');
    Route::post('/dashboard/route-map/{id}/complete', [CollectionSessionController::class, 'completeRoute'])->middleware('auth')->name('dashboard.complete-route');
    Route::post('/dashboard/route-map/{sessionId}/point/{pointId}/status', [CollectionSessionController::class, 'updatePointStatus'])->middleware('auth')->name('dashboard.update-point-status');

    Route::get('/dashboard/truck-full', [CollectionSessionController::class, 'truckFull'])->middleware('auth')->name('dashboard.truck-full');
    Route::post('/dashboard/truck-full', [CollectionSessionController::class, 'logTruckFull'])->middleware('auth')->name('dashboard.log-truck-full');

    Route::get('/dashboard/point-logs', [CollectionSessionController::class, 'pointLogs'])->middleware('auth')->name('dashboard.point-logs');


// Gemini api waste assessment route
Route::post('/api/assess-waste', [WasteAssessmentController::class, 'assess']);

// Report Issue routes (AI-verified)
Route::post('/api/reports/violation', [ReportIssueController::class, 'reportViolation']);
Route::post('/api/reports/missed-collection', [ReportIssueController::class, 'reportMissedCollection']);

// SMS Notifications
Route::post('/api/notify/truck-approaching', [\App\Http\Controllers\SmsNotificationController::class, 'notifyApproaching']);
Route::get('/api/notify/test', [\App\Http\Controllers\SmsNotificationController::class, 'sendTest']);


