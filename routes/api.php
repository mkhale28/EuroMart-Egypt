<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\CouponApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\ReviewApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Auth
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
    Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);

    // Products
    Route::get('/products', [ProductApiController::class, 'index']);
    Route::get('/products/featured', [ProductApiController::class, 'featured']);
    Route::get('/products/new-arrivals', [ProductApiController::class, 'newArrivals']);
    Route::get('/products/best-sellers', [ProductApiController::class, 'bestSellers']);
    Route::get('/products/{product}', [ProductApiController::class, 'show']);
    Route::get('/products/{product}/related', [ProductApiController::class, 'related']);

    // Categories
    Route::get('/categories', [CategoryApiController::class, 'index']);
    Route::get('/categories/{category}', [CategoryApiController::class, 'show']);

    // Reviews
    Route::get('/products/{product}/reviews', [ReviewApiController::class, 'index']);
    Route::middleware('auth:sanctum')->post('/products/{product}/reviews', [ReviewApiController::class, 'store']);

    // Coupons
    Route::post('/coupons/validate', [CouponApiController::class, 'validateCoupon']);

    // Cart (authenticated)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/cart', [CartApiController::class, 'index']);
        Route::post('/cart/add', [CartApiController::class, 'add']);
        Route::patch('/cart/{id}', [CartApiController::class, 'update']);
        Route::delete('/cart/{id}', [CartApiController::class, 'remove']);
        Route::delete('/cart', [CartApiController::class, 'clear']);
    });

    // Orders (authenticated)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/orders', [OrderApiController::class, 'index']);
        Route::get('/orders/{order}', [OrderApiController::class, 'show']);
        Route::post('/orders/track', [OrderApiController::class, 'track']);
    });
});
