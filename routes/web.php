<?php

// Definir rutas

use Core\Route;

use App\Controllers\LoginController;
use App\Controllers\AdminDashboardController;

Route::get('/', [LoginController::class, 'showLoginForm']);

Route::post('/auth/login', [LoginController::class, 'login']);
Route::get('/auth/logout', [LoginController::class, 'logout']);

Route::get('admin/dashboard', [AdminDashboardController::class, 'index']);

Route::dispatch();
