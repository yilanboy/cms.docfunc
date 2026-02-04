<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\PasskeyLoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Middleware\InertiaAuthenticateMiddleware;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::post('/passkey-login', PasskeyLoginController::class)
    ->name('passkey-login')
    ->middleware('guest');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout')
    ->middleware(InertiaAuthenticateMiddleware::class);

Route::get('/email/verify', [VerifyEmailController::class, 'show'])
    ->middleware(InertiaAuthenticateMiddleware::class)
    ->name('verification.notice');

Route::post('/email/verify', [VerifyEmailController::class, 'create'])
    ->middleware([InertiaAuthenticateMiddleware::class, 'throttle:6,1'])
    ->name('verification.send');

Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'store'])
    ->middleware([InertiaAuthenticateMiddleware::class, 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'edit'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [ResetPasswordController::class, 'update'])
    ->middleware(['guest', HandlePrecognitiveRequests::class])
    ->name('password.update');
