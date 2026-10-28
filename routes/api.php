<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPriceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('products', ProductController::class);

    Route::get('/products/{product}/prices', [ProductPriceController::class, 'index']);
    Route::post('/products/{product}/prices', [ProductPriceController::class, 'store']);
});
