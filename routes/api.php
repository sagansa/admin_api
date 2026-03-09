<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PresenceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DetailSalesOrderController;
use App\Http\Controllers\Api\DetailStockCardController;
use App\Http\Controllers\Api\RemainingStorageController;
use App\Http\Controllers\Api\StockMonitoringController;

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
    Route::put('detail-sales-orders/{detailSalesOrder}', [DetailSalesOrderController::class, 'update']);
    Route::delete('detail-sales-orders/{detailSalesOrder}', [DetailSalesOrderController::class, 'destroy']);
    Route::get('detail-sales-orders/by-sales-order/{salesOrderId}', [DetailSalesOrderController::class, 'getBySalesOrder']);
    Route::get('detail-sales-orders-report', [DetailSalesOrderController::class,'report']);

    // RemainingStorage routes
    Route::get('remaining-storages', [RemainingStorageController::class, 'index']);
    Route::post('remaining-storages', [RemainingStorageController::class, 'store']);
    Route::get('remaining-storages/{remainingStorage}', [RemainingStorageController::class, 'show']);
    Route::put('remaining-storages/{remainingStorage}', [RemainingStorageController::class, 'update']);
    Route::delete('remaining-storages/{remainingStorage}', [RemainingStorageController::class, 'destroy']);
    Route::get('remaining-storages-report', [RemainingStorageController::class, 'report']);

    // DetailStockCard routes
    Route::get('detail-stock-cards', [DetailStockCardController::class, 'index']);
    Route::post('detail-stock-cards', [DetailStockCardController::class,'store']);
    Route::get('detail-stock-cards/daily', [DetailStockCardController::class, 'dailyStockCard']);
    Route::get('detail-stock-cards/monthly', [DetailStockCardController::class, 'monthlyStockCard']);
    Route::get('detail-stock-cards/yearly', [DetailStockCardController::class, 'yearlyStockCard']);
    Route::get('detail-stock-cards/{detailStockCard}', [DetailStockCardController::class,'show']);
    Route::put('detail-stock-cards/{detailStockCard}', [DetailStockCardController::class, 'update']);
    Route::delete('detail-stock-cards/{detailStockCard}', [DetailStockCardController::class, 'destroy']);
    Route::get('detail-stock-cards-report', [DetailStockCardController::class,'report']);

    // StockMonitoring routes
    Route::get('stock-monitorings', [StockMonitoringController::class, 'index']);
    Route::post('stock-monitorings', [StockMonitoringController::class, 'store']);
    Route::get('stock-monitorings/{stockMonitoring}', [StockMonitoringController::class, 'show']);
    Route::put('stock-monitorings/{stockMonitoring}', [StockMonitoringController::class, 'update']);
    Route::delete('stock-monitorings/{stockMonitoring}', [StockMonitoringController::class, 'destroy']);
    Route::get('stock-monitorings-report', [StockMonitoringController::class, 'report']);
    Route::get('stock-monitorings-simplified', [StockMonitoringController::class, 'simplified']);
    Route::get('stores-not-reported', [StockMonitoringController::class, 'storesNotReported']);
});
