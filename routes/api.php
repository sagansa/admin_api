<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PresenceController;
use App\Http\Controllers\Api\AuthController;

// Auth routes
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);
    // Presence routes
    Route::get('presences', [PresenceController::class, 'index']);
    Route::post('presences', [PresenceController::class, 'store']);
    Route::get('presences/{presence}', [PresenceController::class, 'show']);
    Route::post('presences/{presence}/check-out', [PresenceController::class, 'checkOut']);
    Route::post('presences/{presence}/approve', [PresenceController::class, 'approve']);
    Route::get('presences-report', [PresenceController::class, 'report']);
});