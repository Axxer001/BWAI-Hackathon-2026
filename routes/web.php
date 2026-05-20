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

Route::get('/dashboard', function () {
    return view('dashboard.index'); // This will load index.blade.php which extends layout.blade.php
})->middleware('auth')->name('dashboard');


// Gemini api waste assessment route
Route::post('/api/assess-waste', [WasteAssessmentController::class, 'assess']);

// Include the map routes
require __DIR__.'/map.php';
