<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PresenceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\API\DetailSalesOrderController;

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

    // DetailSalesOrder routes
    Route::get('detail-sales-orders', [DetailSalesOrderController::class, 'index']);
    Route::post('detail-sales-orders', [DetailSalesOrderController::class, 'store']);
    Route::get('detail-sales-orders/{detailSalesOrder}', [DetailSalesOrderController::class, 'show']);
    Route::get('detail-sales-orders-report', [DetailSalesOrderController::class,'report']);

    // Route::apiResource('detail-sales-orders', DetailSalesOrderController::class);
    //     Route::get('sales-order/{salesOrderId}/details', [DetailSalesOrderController::class, 'getBySalesOrder']);
});