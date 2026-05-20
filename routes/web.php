<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;

Route::get('/', function () {
    return view('index');
});

// View the interactive map
Route::get('/map', [MapController::class, 'index']);

// API routes for map data (fetching and saving)
Route::get('/api/garbage-points', [MapController::class, 'getPoints']);
Route::post('/api/garbage-points', [MapController::class, 'storePoint']);