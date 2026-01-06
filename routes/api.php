<?php

use App\Http\Controllers\VinaController;
use Illuminate\Support\Facades\Route;

Route::get('/vina-2026/parrilla', [VinaController::class, 'getParrilla']);
Route::get('/vina-2026/dia/{dia}', [VinaController::class, 'getDia']);
