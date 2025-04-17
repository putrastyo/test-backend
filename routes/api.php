<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TheaterController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function() {
    Route::apiResource('movies', MovieController::class);
    Route::apiResource('theaters', TheaterController::class);
    Route::get('logout', [AuthController::class, 'logout']);
});

Route::middleware('guest')->group(function() {
    Route::post('login', [AuthController::class, 'login']);
});
