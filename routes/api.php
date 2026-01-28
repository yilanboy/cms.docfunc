<?php

use App\Http\Controllers\Api\GeneratePasskeyAuthenticationOptionsController;
use App\Http\Controllers\Api\GeneratePasskeyRegistrationOptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/passkeys/register-options', GeneratePasskeyRegistrationOptionController::class)
    ->name('passkeys.register-options')
    ->middleware('auth:sanctum');

Route::get('/passkeys/authentication-options', GeneratePasskeyAuthenticationOptionsController::class)
    ->name('passkeys.authentication-options');
