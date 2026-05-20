<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;

// View the Map
Route::get('/map', [MapController::class, 'index']);

// API Routes for Map
Route::get('/api/garbage-points', [MapController::class, 'getGarbagePoints']);
Route::post('/api/garbage-points', [MapController::class, 'addGarbagePoint']);
Route::post('/api/garbage-points/{id}/toggle', [MapController::class, 'togglePoint']);
Route::post('/api/garbage-points/{id}/move', [MapController::class, 'movePoint']);