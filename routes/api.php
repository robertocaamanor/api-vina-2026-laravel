<?php

use App\Http\Controllers\VinaController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
	Route::post('/login', [AuthController::class, 'login']);
	Route::get('/me', [AuthController::class, 'me']);
	Route::post('/logout', [AuthController::class, 'logout']);
	Route::post('/refresh', [AuthController::class, 'refresh']);
});

Route::get('/vina-2026/parrilla', [VinaController::class, 'getParrilla']);
Route::get('/vina-2026/dia/{dia}', [VinaController::class, 'getDia']);
