<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

require __DIR__ . '/auth.php';

// ── Product management (admin + product_manager) ─────────────────────────────
Route::middleware(['auth', 'role:admin,product_manager'])->group(function () {
    Route::get('dashboard',              [DashboardController::class, 'index'])->name('dashboard');
    Route::post('orders/{order}/status', [DashboardController::class, 'updateOrderStatus'])->name('orders.updateStatus');

    Route::resource('products', ProductController::class);

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
