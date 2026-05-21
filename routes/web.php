<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\WasteAssessmentController;
use App\Http\Controllers\ReportIssueController;
use App\Http\Controllers\MapController;
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

Route::get('/dashboard/points-map', function () {
    $user = auth()->user();

    // 1. Get user's active assigned point from the relationship
    $collectionPoint = $user->garbagePoints()->wherePivot('is_active', true)->first();

    // 2. Get all active garbage points in their barangay for selection
    $barangayPoints = \App\Models\GarbagePoint::where('barangay_id', $user->barangay_id)
        ->where('is_active', true)
        ->get();

    // 3. Check if a garbage truck is currently on route in their barangay
    $activeSession = \App\Models\CollectionSession::where('barangay_id', $user->barangay_id)
        ->where('status', 'ongoing')
        ->first();

    // 4. Load session points if there is an active collection session
    $sessionPoints = collect();
    if ($activeSession) {
        $sessionPoints = \App\Models\SessionPoint::where('session_id', $activeSession->id)
            ->with('garbagePoint')
            ->orderBy('route_order')
            ->get();
    }

    // 5. Get today's collection schedule
    $today = strtolower(now()->format('l')); // e.g., 'monday'
    $todaySchedule = \App\Models\CollectionSchedule::where('barangay_id', $user->barangay_id)
        ->where(fn($q) => $q->where('day_of_week', $today)->orWhere('day_of_week', 'everyday'))
        ->where('is_active', true)
        ->first();

    return view('dashboard.partials.user.my-collection-point', compact(
        'collectionPoint',
        'barangayPoints',
        'activeSession',
        'sessionPoints',
        'todaySchedule'
    ));
})->middleware('auth')->name('dashboard.points-map');

Route::post('/dashboard/assign-collection-point', function (Illuminate\Http\Request $request) {
    $request->validate([
        'garbage_point_id' => 'required|uuid|exists:garbage_points,id',
    ]);

    $user = auth()->user();

    // Deactivate existing assignments
    \App\Models\UserPointAssignment::where('user_id', $user->id)
        ->update(['is_active' => false]);

    // Create or reactivate the selected point assignment
    \App\Models\UserPointAssignment::updateOrCreate(
        ['user_id' => $user->id, 'garbage_point_id' => $request->garbage_point_id],
        ['is_active' => true, 'assigned_at' => now()]
    );

    return redirect()->back()->with('success', 'Collection point assigned successfully!');
})->middleware('auth')->name('dashboard.assign-point');

Route::post('/dashboard/change-collection-point', function () {
    $user = auth()->user();

    \App\Models\UserPointAssignment::where('user_id', $user->id)
        ->update(['is_active' => false]);

    return redirect()->back()->with('success', 'Please choose a new collection point from the map.');
})->middleware('auth')->name('dashboard.change-point');

// Dashboard Routing Group (Protected by Auth)
Route::middleware('auth')->group(function () {

    // User: Eco-Points View
    Route::get('/dashboard/points', function () {
        // Points to: resources/views/dashboard/partials/user/my-eco-points.blade.php
        return view('dashboard.partials.user.my-eco-points');
    })->name('dashboard.points');


    // 💡 Add the rest of your user routes below as you build the views:


    Route::get('/dashboard/ai-scanner', [WasteAssessmentController::class, 'showScanner'])->name('dashboard.ai-scanner');


    Route::get('/dashboard/report-missed', [ReportIssueController::class, 'createMissedCollectionForm'])->name('reports.missed.create');

});
// API routes for map data (fetching and saving)
Route::get('/api/garbage-points', [MapController::class, 'getGarbagePoints']);
Route::post('/api/garbage-points', [MapController::class, 'addGarbagePoint']);

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

// ─── BARANGAY ROUTES ─────────────────────────────────────────────────

Route::get('/dashboard/schedules', function () {
    $barangayId = auth()->user()->barangay_id;

    // 1. Get the schedules and load their relationships (trucks, collectors, points)
    $schedules = \App\Models\CollectionSchedule::with(['truck', 'collector', 'garbagePoints'])
        ->where('barangay_id', $barangayId)
        ->get();

    // Group schedules by name to display one row per route
    $groupedSchedules = $schedules->groupBy('name')->map(function ($group) {
        $first = $group->first();
        // Format days: 'monday' -> 'Mon', etc.
        $days = $group->pluck('day_of_week')->map(function ($day) {
            if ($day === 'everyday') return 'Every Day';
            $abbreviations = [
                'monday' => 'M', 'tuesday' => 'T', 'wednesday' => 'W',
                'thursday' => 'TH', 'friday' => 'F', 'saturday' => 'Sat', 'sunday' => 'Sun'
            ];
            return $abbreviations[strtolower($day)] ?? ucfirst(substr($day, 0, 3));
        })->toArray();
        
        $first->formatted_days = implode(', ', $days);
        $first->grouped_count = $group->count();
        $first->schedule_ids = $group->pluck('id')->toArray();
        return $first;
    })->values();

    // 2. Calculate Stats
    $activeRoutes = $groupedSchedules->where('is_active', true)->count();
    $assignedPersonnel = \App\Models\User::where('barangay_id', $barangayId)
        ->where('role', 'collector')
        ->count();

    // 3. Fetch data for the form dropdowns
    $trucks = \App\Models\Truck::where('barangay_id', $barangayId)->where('is_active', true)->get();
    $collectors = \App\Models\User::where('barangay_id', $barangayId)->where('role', 'collector')->get();
    $garbagePoints = \App\Models\GarbagePoint::where('barangay_id', $barangayId)->where('is_active', true)->get();

    // 4. Pass EVERYTHING to the view
    return view('dashboard.partials.barangay.schedules', compact(
        'schedules',
        'groupedSchedules',
        'activeRoutes',
        'assignedPersonnel',
        'trucks',
        'collectors',
        'garbagePoints'
    ));
})->middleware('auth')->name('dashboard.schedules');

Route::post('/dashboard/schedules', function (\Illuminate\Http\Request $request) {
    // 1. Validate the new form array data
    $request->validate([
        'name' => 'nullable|string|max:255',
        'days_of_week' => 'required_unless:frequency,daily|array',
        'days_of_week.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
        'collection_time' => 'required',
        'frequency' => 'required|in:daily,weekly,bi-weekly,monthly',
        'truck_id' => 'nullable|exists:trucks,id',
        'default_truck_id' => 'nullable|exists:trucks,id',
        'collector_id' => 'nullable|exists:users,id',
        'default_collector_id' => 'nullable|exists:users,id',
        'garbage_points' => 'required|array', // Validates the dropdown
        'garbage_points.*' => 'exists:garbage_points,id'
    ]);

    // 2. Determine days of week to save: single 'everyday' row if frequency is daily
    $days = $request->frequency === 'daily' ? ['everyday'] : $request->days_of_week;

    $truckId = $request->default_truck_id ?? $request->truck_id;
    $collectorId = $request->default_collector_id ?? $request->collector_id;

    foreach ($days as $day) {
        $schedule = \App\Models\CollectionSchedule::create([
            'name' => $request->name,
            'barangay_id' => auth()->user()->barangay_id,
            'day_of_week' => $day,
            'collection_time' => $request->collection_time,
            'frequency' => $request->frequency,
            'default_truck_id' => $truckId,
            'default_collector_id' => $collectorId,
            'is_active' => true,
        ]);

        // 3. Attach the garbage points to the pivot table with sequence order
        if ($request->has('garbage_points')) {
            $syncData = [];
            foreach ($request->garbage_points as $index => $pointId) {
                $syncData[$pointId] = ['sequence' => $index + 1];
            }
            $schedule->garbagePoints()->sync($syncData);
        }
    }

    return redirect()->back()->with('success', 'Collection schedules saved and activated successfully!');
})->middleware('auth')->name('dashboard.schedules.store');

Route::post('/dashboard/schedules/bulk-delete', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'exists:collection_schedules,id'
    ]);

    \App\Models\CollectionSchedule::destroy($request->ids);

    return redirect()->back()->with('success', 'Selected schedules deleted successfully!');
})->middleware('auth')->name('dashboard.schedules.bulk-delete');

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
    $sessions = \App\Models\CollectionSession::where('status', 'ongoing')
        ->where('barangay_id', $barangayId)
        ->with(['collector', 'sessionPoints.garbagePoint'])
        ->get();
    return response()->json($sessions);
})->name('api.barangay.live-tracking');

Route::get('/dashboard/tickets-missed', function () {
    $user = auth()->user();

    $query = \App\Models\MissedCollectionReport::with(['reporter', 'collectionPoint'])
        ->orderBy('created_at', 'desc');

    // Filter only if it's a barangay admin
    if (strtolower($user->role) === 'barangay' && $user->barangay_id) {
        $query->whereHas('collectionPoint', function ($q) use ($user) {
            $q->where('barangay_id', $user->barangay_id);
        });
    }

    $reports = $query->get();

    return view('dashboard.partials.barangay.tickets-missed', compact('reports'));
})->middleware('auth')->name('dashboard.tickets-missed');

Route::post('/dashboard/tickets-missed/{id}/action', function (\Illuminate\Http\Request $request, $id) {
    $report = \App\Models\MissedCollectionReport::findOrFail($id);
    $request->validate(['status' => 'required|in:resolved,invalid']);
    $report->update(['status' => $request->status]);
    return redirect()->back()->with('success', 'Missed collection ticket updated successfully!');
})->middleware('auth')->name('dashboard.tickets-missed.action');

Route::get('/dashboard/tickets-violations', function () {
    $user = auth()->user();

    // 1. Start the query
    $query = \App\Models\ViolationReport::with('reporter')->orderBy('created_at', 'desc');

    // 2. If the logged-in user is a barangay admin, strictly filter by their barangay
    if (strtolower($user->role) === 'barangay' && $user->barangay_id) {
        $query->where('barangay_id', $user->barangay_id);
    }

    // 3. Execute query and load view
    $reports = $query->get();

    return view('dashboard.partials.barangay.tickets-violations', compact('reports'));
})->middleware('auth')->name('dashboard.tickets-violations');

Route::post('/dashboard/tickets-violations/{id}/action', function (\Illuminate\Http\Request $request, $id) {
    $report = \App\Models\ViolationReport::findOrFail($id);
    $request->validate(['status' => 'required|in:pending,under_review,fined,dismissed,investigating,resolved']);

    $statusMap = [
        'investigating' => 'under_review',
        'resolved' => 'fined',
    ];

    $newStatus = $statusMap[$request->status] ?? $request->status;
    $report->update(['status' => $newStatus]);
    return redirect()->back()->with('success', 'Violation ticket updated successfully!');
})->middleware('auth')->name('dashboard.tickets-violations.action');

// ─── ADMIN ROUTES ────────────────────────────────────────────────────

Route::get('/admin/overview', function () {
    $totalBarangays = \App\Models\Barangay::count();
    $totalEcoPoints = \DB::table('eco_points_transactions')->sum('points');
    $totalWaste = \App\Models\CollectionReport::sum('completed_points') * 0.1;
    $totalTrucks = \App\Models\Truck::count();
    $totalAiScans = \App\Models\WasteScan::count();
    $totalGarbageCollected = \App\Models\SessionPoint::where('status', 'collected')->count();
    $barangays = \App\Models\Barangay::withCount(['users', 'trucks', 'collectionPoints'])->get();

    return view('dashboard.partials.admin.overview', compact('totalBarangays', 'totalEcoPoints', 'totalWaste', 'totalTrucks', 'totalAiScans', 'totalGarbageCollected', 'barangays'));
})->name('admin.overview');

Route::get('/admin/users', function (\Illuminate\Http\Request $request) {
    $search = $request->query('search');
    $role = $request->query('role');

    $query = \App\Models\User::with('barangay');

    if ($search) {
        $query->where(function ($q) use ($search) {
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
        'email' => 'required|email|unique:users,email,' . $id,
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
    $totalAiScans = \App\Models\WasteScan::count();
    $totalGarbageCollected = \App\Models\SessionPoint::where('status', 'collected')->count();
    $barangays = \App\Models\Barangay::all()->map(function ($b) {
        $sessions = \App\Models\CollectionSession::where('barangay_id', $b->id)->pluck('id');
        $reports = \App\Models\CollectionReport::whereIn('session_id', $sessions)->get();
        $b->completed_count = $reports->count();
        $b->waste_collected = $reports->sum('completed_points') * 0.1;
        $b->points_distributed = $reports->sum('total_points');
        $b->avg_completion = $b->completed_count > 0
            ? round($reports->avg(function ($r) {
                return $r->completionRate();
            }), 2)
            : 0;
        return $b;
    });
    return view('dashboard.partials.admin.analytics', compact('totalEcoPoints', 'totalWaste', 'totalViolations', 'totalAiScans', 'totalGarbageCollected', 'barangays'));
})->name('admin.analytics');

Route::get('/admin/analytics/download', function () {
    $headers = [
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=barangay_efficiency_report.csv",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
    ];

    $barangays = \App\Models\Barangay::all();

    $callback = function () use ($barangays) {
        $file = fopen('php://output', 'w');
        fputcsv($file, ['Barangay Name', 'Completed Sessions', 'Total Notified Residents', 'Total Eco-Points Distributed', 'Avg Completion Rate (%)']);

        foreach ($barangays as $barangay) {
            $sessions = \App\Models\CollectionSession::where('barangay_id', $barangay->id)->pluck('id');
            $reports = \App\Models\CollectionReport::whereIn('session_id', $sessions)->get();

            $completedCount = $reports->count();
            $totalNotified = $reports->sum('total_notified_users');
            $totalPoints = $reports->sum('total_points');

            $avgCompletion = $completedCount > 0
                ? round($reports->avg(function ($r) {
                    return $r->completionRate();
                }), 2)
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
// Gemini api waste assessment route
Route::post('/api/assess-waste', [WasteAssessmentController::class, 'assess']);

// Report Issue routes (AI-verified)
Route::post('/api/reports/violation', [ReportIssueController::class, 'reportViolation']);
Route::post('/api/reports/missed-collection', [ReportIssueController::class, 'reportMissedCollection']);


// Email Notifications
Route::post('/api/notify/truck-approaching', [\App\Http\Controllers\NotificationController::class, 'notifyApproaching']);
Route::get('/api/notify/test', [\App\Http\Controllers\NotificationController::class, 'sendTest']);


Route::middleware(['auth'])->group(function () {
    // Show the form (GET) - This will now properly trigger your controller!
    Route::get('/dashboard/report-violation', [ReportIssueController::class, 'createViolationForm'])->name('reports.create');
    Route::get('/dashboard/report-violation/{id}', [ReportIssueController::class, 'showViolationReport'])->name('reports.violation.show');

    // Handle the submission (POST)
    Route::post('/dashboard/report-violation', [ReportIssueController::class, 'reportViolation'])->name('reports.store');

    // Missed pickup report routes
    Route::get('/dashboard/report-missed/{id}', [ReportIssueController::class, 'showMissedCollectionReport'])->name('reports.missed.show');
    Route::post('/dashboard/report-missed', [ReportIssueController::class, 'reportMissedCollection'])->name('reports.missed.store');
});