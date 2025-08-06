<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\RootController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\TagController;
use App\Http\Middleware\InertiaAuthenticateMiddleware;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::get('/', RootController::class)->name('root');

Route::middleware([InertiaAuthenticateMiddleware::class, 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/links', [LinkController::class, 'index'])
        ->name('links.index');

    Route::post('/links', [LinkController::class, 'store'])
        ->name('links.store');

    Route::patch('/links/{link}', [LinkController::class, 'update'])
        ->name('links.update');

    Route::get('/tags', [TagController::class, 'index'])
        ->name('tags.index');

    Route::post('/tags', [TagController::class, 'store'])
        ->name('tags.store');

    Route::patch('/tags/{tag}', [TagController::class, 'update'])
        ->name('tags.update');

    Route::get('/settings/profile', [ProfileController::class, 'edit'])
        ->name('settings.profile.edit');

    Route::patch('/settings/profile', [ProfileController::class, 'update'])
        ->name('settings.profile.update');

    Route::get('/settings/password', [PasswordController::class, 'edit'])
        ->name('settings.password.edit');

    Route::put('/settings/password', [PasswordController::class, 'update'])
        ->name('settings.password.update');
});
