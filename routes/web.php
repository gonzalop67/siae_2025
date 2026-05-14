<?php

// Definir rutas

use Core\Route;

require_once RUTA_APP . '/Core/middlewares.php';

use App\Controllers\LoginController;
use App\Controllers\AdminDashboardController;
use App\Controllers\UserController;

Route::get('/', [LoginController::class, 'showLoginForm']);

Route::post('/auth/login', [LoginController::class, 'login']);
Route::get('/auth/logout', [LoginController::class, 'logout']);

Route::get('admin/dashboard', [AdminDashboardController::class, 'index'], [$authMiddleware]);

/** Rutas para Usuarios */
Route::get('/users', [UserController::class, 'index'], [$authMiddleware]);

Route::dispatch();
