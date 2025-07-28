<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Middleware\InertiaAuthenticateMiddleware;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware([InertiaAuthenticateMiddleware::class, 'verified']);

Route::get('/links', [LinkController::class, 'index'])
    ->name('links.index')
    ->middleware([InertiaAuthenticateMiddleware::class, 'verified']);

Route::get('/settings/profile', [ProfileController::class, 'edit'])
    ->name('settings.profile.edit')
    ->middleware([InertiaAuthenticateMiddleware::class, 'verified']);

Route::patch('/settings/profile', [ProfileController::class, 'update'])
    ->name('settings.profile.update')
    ->middleware([InertiaAuthenticateMiddleware::class, 'verified']);

Route::get('/settings/password', [PasswordController::class, 'edit'])
    ->name('settings.password.edit')
    ->middleware([InertiaAuthenticateMiddleware::class, 'verified']);

Route::put('/settings/password', [PasswordController::class, 'update'])
    ->name('settings.password.update')
    ->middleware([InertiaAuthenticateMiddleware::class, 'verified']);
