<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\RootController;
use App\Http\Controllers\Settings\PasskeyController;
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

    Route::resource('links', LinkController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('tags', TagController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::patch('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');

        Route::get('/password', [PasswordController::class, 'edit'])
            ->name('password.edit');

        Route::put('/password', [PasswordController::class, 'update'])
            ->name('password.update');

        Route::get('/passkeys', [PasskeyController::class, 'edit'])
            ->name('passkey.edit');

        Route::post('/passkeys', [PasskeyController::class, 'store'])
            ->name('passkey.store');
    });
});
