<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Register\AuthController;
use App\Http\Controllers\Register\UserController;
use App\Http\Controllers\TravelOrders\TravelOrderController;

// Route::get('/test', function () {// testar se a api está funcionando normalmente
//     return response()->json(['message' => 'API is working!']);
// });

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
     Route::apiResource('users', UserController::class);//rota disponível apenas na API por enquanto
     Route::post('/logout', [AuthController::class, 'logout']);
     
     Route::prefix('travel-orders')->name('travel-orders.')->group(function () {
        Route::get('/', [TravelOrderController::class, 'index'])->name('index');
        Route::post('/', [TravelOrderController::class, 'store'])->name('store');
        Route::get('{id}', [TravelOrderController::class, 'show'])->name('show');
        Route::patch('{id}', [TravelOrderController::class, 'update'])->name('update');
        Route::delete('{id}', [TravelOrderController::class, 'destroy'])->name('destroy');
        Route::put('{id}/status', [TravelOrderController::class, 'updateStatus'])->name('update-status');
    });
});