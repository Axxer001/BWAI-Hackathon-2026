<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResourceController;

use App\Http\Controllers\WasteAssessmentController;

Route::apiResource('resources',ResourceController::class);
Route::post('/assess-waste', [WasteAssessmentController::class, 'assess']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
