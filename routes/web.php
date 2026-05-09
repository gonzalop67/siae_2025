<?php

// Definir rutas
use Core\Route;

use App\Controllers\LoginController;

Route::get('/', [LoginController::class, 'showLoginForm']);

Route::post('/auth/login', [LoginController::class, 'login']);

Route::dispatch();
