<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\OrderController;
use App\Http\Controllers\Shop\ShopController;
use Illuminate\Support\Facades\Route;

// Public redirect
Route::get('/', fn () => redirect()->route('shop.index'));

require __DIR__ . '/auth.php';

// ── Customer-facing shop (public) ────────────────────────────────────────────
Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/',          [ShopController::class, 'index'])->name('index');
    Route::get('/{product}', [ShopController::class, 'show'])->name('show');
});

// ── Cart (customers only) ────────────────────────────────────────────────────
Route::prefix('cart')->name('shop.')->middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/',                      [CartController::class, 'index'])->name('cart');
    Route::post('/add/{product}',        [CartController::class, 'add'])->name('cart.add');
    Route::patch('/update/{productId}',  [CartController::class, 'update'])->name('cart.update');
    Route::delete('/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/',                   [CartController::class, 'clear'])->name('cart.clear');
});

// ── Orders (customers only) ──────────────────────────────────────────────────
Route::prefix('orders')->name('shop.')->middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/',          [OrderController::class, 'index'])->name('orders');
    Route::get('/checkout',  [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
    Route::get('/{order}',   [OrderController::class, 'show'])->name('order.show');
});

// ── Product management (admin + product_manager) ─────────────────────────────
Route::middleware(['auth', 'role:admin,product_manager'])->group(function () {
    Route::get('dashboard',              [DashboardController::class, 'index'])->name('dashboard');
    Route::post('orders/{order}/status', [DashboardController::class, 'updateOrderStatus'])->name('orders.updateStatus');

    Route::resource('products', ProductController::class);

    // Manager resubmit rejected product
    Route::post('products/{product}/submit', [ProductController::class, 'submit'])->name('products.submit');
});

// ── Admin-only: product approval ─────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('products/{product}/approve', [ProductController::class, 'approve'])->name('products.approve');
    Route::post('products/{product}/reject',  [ProductController::class, 'reject'])->name('products.reject');
});

// ── Admin panel ──────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('users',           [UserController::class, 'index'])->name('users.index');
    Route::get('users/create',    [UserController::class, 'create'])->name('users.create');
    Route::post('users',          [UserController::class, 'store'])->name('users.store');
    Route::patch('users/{user}',  [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});
