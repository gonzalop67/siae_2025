<?php

// Definir rutas
use Core\Route;

use App\Controllers\LoginController;

Route::get('/', [LoginController::class, 'showLoginForm']);

Route::dispatch();
