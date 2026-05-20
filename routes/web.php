<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\WasteAssessmentController;

// Landing Page
Route::get('/', function () {
    return view('index');
});

// Authentication Group
Route::group(['prefix' => 'auth'], function () {
    
    // Login Routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login'); // Changing this to 'login' fixes the error!
    Route::post('/login', [LoginController::class, 'login']);
    
    // Registration Routes
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
});
// View the interactive map
Route::get('/map', [MapController::class, 'index']);

// API routes for map data (fetching and saving)
Route::get('/api/garbage-points', [MapController::class, 'getPoints']);
Route::post('/api/garbage-points', [MapController::class, 'storePoint']);
Route::post('/api/assess-waste', [WasteAssessmentController::class, 'assess']);