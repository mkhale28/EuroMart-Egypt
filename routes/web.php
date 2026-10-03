<?php

use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Customer\WishlistController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [CustomerProductController::class, 'index'])->name('index');
    Route::get('/search', [CustomerProductController::class, 'search'])->name('search');
    Route::get('/category/{slug}', [CustomerProductController::class, 'byCategory'])->name('category');
    Route::get('/{product}', [CustomerProductController::class, 'show'])->name('show');
});

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/{cartId}', [CartController::class, 'update'])->name('update');
    Route::delete('/{cartId}', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
    Route::get('/count', [CartController::class, 'count'])->name('count');
});

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/process', [CheckoutController::class, 'process'])->name('process');
    Route::post('/validate-coupon', [CheckoutController::class, 'validateCoupon'])->name('validate-coupon');
});

Route::prefix('orders')->name('orders.')->group(function () {
    Route::get('/confirmation/{orderNumber}', [OrderController::class, 'confirmation'])->name('confirmation');
    Route::get('/track', [OrderController::class, 'trackForm'])->name('track');
    Route::post('/track', [OrderController::class, 'track'])->name('track.post');
    Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
});

Route::post('/reviews/{product}', [ReviewController::class, 'store'])->name('reviews.store');

Route::middleware(['auth'])->group(function () {
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('index');
        Route::post('/toggle/{product}', [WishlistController::class, 'toggle'])->name('toggle');
        Route::delete('/{id}', [WishlistController::class, 'remove'])->name('remove');
    });

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
        Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password', [DashboardController::class, 'updatePassword'])->name('password.update');
        Route::get('/orders', [DashboardController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [DashboardController::class, 'orderShow'])->name('orders.show');
        Route::get('/addresses', [DashboardController::class, 'addresses'])->name('addresses');
        Route::post('/addresses', [DashboardController::class, 'storeAddress'])->name('addresses.store');
        Route::put('/addresses/{address}', [DashboardController::class, 'updateAddress'])->name('addresses.update');
        Route::delete('/addresses/{address}', [DashboardController::class, 'deleteAddress'])->name('addresses.delete');
    });
});

require __DIR__ . '/auth.php';
