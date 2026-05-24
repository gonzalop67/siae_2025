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
Route::get('/users/create', [UserController::class, 'create'], [$authMiddleware]);
Route::post('/users', [UserController::class, 'store'], [$authMiddleware]);

// Ver el listado de la papelera (GET)
Route::get('/users/wastebasket', [UserController::class, 'wastebasket']);
Route::post('/users/:id/restore', [UserController::class, 'restore']);
Route::post('/users/:id/destroy', [UserController::class, 'destroy']);

Route::get('/users/:id/edit', [UserController::class, 'edit'], [$authMiddleware]);
Route::post('/users/:id/update', [UserController::class, 'update'], [$authMiddleware]);
Route::get('/users/:id/roles', [UserController::class, 'roles'], [$authMiddleware]);
Route::post('/users/:id/roles', [UserController::class, 'updateRoles'], [$authMiddleware]);
// Ruta para la eliminación "suave"
Route::post('/users/:id/delete', [UserController::class, 'delete'], [$authMiddleware]);

Route::dispatch();
