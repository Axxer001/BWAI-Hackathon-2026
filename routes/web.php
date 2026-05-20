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
    return view('dashboard.index'); // This will load index.blade.php which extends layout.blade.php
})->middleware('auth')->name('dashboard');

// ─── BARANGAY ROUTES ─────────────────────────────────────────────────

    Route::get('/dashboard/schedules', function () {
        return view('dashboard.partials.barangay.schedules'); 
    })->name('dashboard.schedules');

    Route::get('/dashboard/fleet', function () {
        return view('dashboard.partials.barangay.fleet'); 
    })->name('dashboard.fleet');

    Route::get('/dashboard/points-manage', function () {
        return view('dashboard.partials.barangay.points-manage'); 
    })->name('dashboard.points-manage');

    Route::get('/dashboard/tickets-missed', function () {
        return view('dashboard.partials.barangay.tickets-missed'); 
    })->name('dashboard.tickets-missed');

    Route::get('/dashboard/tickets-violations', function () {
        return view('dashboard.partials.barangay.tickets-violations'); 
    })->name('dashboard.tickets-violations');

// ─── ADMIN ROUTES ────────────────────────────────────────────────────

    Route::get('/admin/users', function () {
        return view('dashboard.partials.admin.users'); 
    })->name('admin.users');

    Route::get('/admin/barangays', function () {
        return view('dashboard.partials.admin.barangays'); 
    })->name('admin.barangays');

    Route::get('/admin/analytics', function () {
        return view('dashboard.partials.admin.analytics'); 
    })->name('admin.analytics');

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

// Include the map routes
require __DIR__.'/map.php';
