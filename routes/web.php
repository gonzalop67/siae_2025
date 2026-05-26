<?php

// Definir rutas

use Core\Route;

require_once RUTA_APP . '/Core/middlewares.php';

use App\Controllers\LoginController;
use App\Controllers\AdminDashboardController;
use App\Controllers\PermissionController;
use App\Controllers\RoleController;
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
Route::get('/users/wastebasket', [UserController::class, 'wastebasket'], [$authMiddleware]);
Route::post('/users/:id/restore', [UserController::class, 'restore'], [$authMiddleware]);
Route::post('/users/:id/destroy', [UserController::class, 'destroy'], [$authMiddleware]);
// Ruta para la eliminación "suave"
Route::post('/users/:id/delete', [UserController::class, 'delete'], [$authMiddleware]);

Route::get('/users/:id/edit', [UserController::class, 'edit'], [$authMiddleware]);
Route::post('/users/:id/update', [UserController::class, 'update'], [$authMiddleware]);
Route::get('/users/:id/roles', [UserController::class, 'roles'], [$authMiddleware]);
Route::post('/users/:id/roles', [UserController::class, 'updateRoles'], [$authMiddleware]);

/** Rutas para Roles */
Route::get('/roles', [RoleController::class, 'index'], [$authMiddleware]);
Route::get('/roles/create', [RoleController::class, 'create'], [$authMiddleware]);
Route::post('/roles', [RoleController::class, 'store'], [$authMiddleware]);
// Ver el listado de la papelera (GET)
Route::get('/roles/wastebasket', [RoleController::class, 'wastebasket'], [$authMiddleware]);
Route::post('/roles/:id/restore', [RoleController::class, 'restore'], [$authMiddleware]);
Route::post('/roles/:id/destroy', [RoleController::class, 'destroy'], [$authMiddleware]);

Route::get('/roles/:id/edit', [RoleController::class, 'edit'], [$authMiddleware]);
Route::post('/roles/:id/update', [RoleController::class, 'update'], [$authMiddleware]);
// Ruta para la eliminación "suave"
Route::post('/roles/:id/delete', [RoleController::class, 'delete'], [$authMiddleware]);

/** Rutas para Permisos */
Route::get('/permissions', [PermissionController::class, 'index'], [$authMiddleware]);
Route::get('/permissions/create', [PermissionController::class, 'create'], [$authMiddleware]);
Route::post('/permissions', [PermissionController::class, 'store'], [$authMiddleware]);

Route::dispatch();
