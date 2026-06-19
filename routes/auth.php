<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\OtpLoginController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login',  [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // OTP-based login
    Route::get('login/otp',         [OtpLoginController::class, 'showRequestForm'])->name('otp.request.form');
    Route::post('login/otp',        [OtpLoginController::class, 'sendOtp'])->name('otp.request');
    Route::get('login/otp/verify',  [OtpLoginController::class, 'showVerifyForm'])->name('otp.verify.form');
    Route::post('login/otp/verify', [OtpLoginController::class, 'verifyOtp'])->name('otp.verify');
    Route::post('login/otp/resend', [OtpLoginController::class, 'resendOtp'])->name('otp.resend');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
