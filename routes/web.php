<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\WasteAssessmentController;

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
    
    // Main Dashboard Home
    Route::get('/dashboard', function () {
        return view('dashboard.index'); 
    })->name('dashboard');

    // User: Eco-Points View
    Route::get('/dashboard/points', function () {
        // Points to: resources/views/dashboard/partials/user/my-eco-points.blade.php
        return view('dashboard.partials.user.my-eco-points'); 
    })->name('dashboard.points');

});
// API routes for map data (fetching and saving)
Route::get('/api/garbage-points', [MapController::class, 'getPoints']);
Route::post('/api/garbage-points', [MapController::class, 'storePoint']);

Route::get('/dashboard', function () {
    return view('dashboard.index'); // This will load index.blade.php which extends layout.blade.php
})->middleware('auth')->name('dashboard');


// Gemini api waste assessment route
Route::post('/api/assess-waste', [WasteAssessmentController::class, 'assess']);

// Include the map routes
require __DIR__.'/map.php';
