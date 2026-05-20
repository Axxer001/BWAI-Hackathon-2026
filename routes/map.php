<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;

// View the Map
Route::get('/map', [MapController::class, 'index']);

// API Routes for Map
Route::get('/api/garbage-points', [MapController::class, 'getGarbagePoints']);
Route::post('/api/garbage-points', [MapController::class, 'addGarbagePoint']);