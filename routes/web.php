<?php

// Definir rutas

use App\Controllers\Admin\AdminDashboardController;
use App\Controllers\Admin\InstitucionController;
use App\Controllers\Admin\MenuController;
use App\Controllers\Admin\Oferta_educativaController;
use App\Controllers\Admin\PermissionController;
use App\Controllers\Admin\RoleController;
use App\Controllers\Admin\TaskController;
use App\Controllers\Admin\UserController;
use App\Controllers\LoginController;

use Core\Route;

// Ahora sí encontrará perfectamente la carpeta Core en la raíz del proyecto
require_once RAIZ_PROYECTO . '/Core/middlewares.php';

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
// Rutas comunes
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
// Rutas comunes
Route::get('/roles/:id/edit', [RoleController::class, 'edit'], [$authMiddleware]);
Route::post('/roles/:id/update', [RoleController::class, 'update'], [$authMiddleware]);
Route::get('/roles/:id/permissions', [RoleController::class, 'permissions'], [$authMiddleware]);
Route::post('/roles/:id/permissions', [RoleController::class, 'updatePermissions'], [$authMiddleware]);
// Ruta para la eliminación "suave"
Route::post('/roles/:id/delete', [RoleController::class, 'delete'], [$authMiddleware]);

/** Rutas para Permisos */
Route::get('/permissions', [PermissionController::class, 'index'], [$authMiddleware]);
Route::get('/permissions/create', [PermissionController::class, 'create'], [$authMiddleware]);
Route::post('/permissions', [PermissionController::class, 'store'], [$authMiddleware]);
Route::get('/permissions/:id/edit', [PermissionController::class, 'edit'], [$authMiddleware]);
Route::post('/permissions/:id/update', [PermissionController::class, 'update'], [$authMiddleware]);

/** Rutas para Menús */
Route::get('/menus', [MenuController::class, 'index'], [$authMiddleware]);
Route::post('/menus/get_menu_ajax', [MenuController::class, 'get_menu_ajax'], [$authMiddleware]);
Route::post('/menus/guardar_orden_ajax', [MenuController::class, 'guardar_orden_ajax'], [$authMiddleware]);
Route::post('/menus/store', [MenuController::class, 'store'], [$authMiddleware]);
Route::post('/menus/:id/edit', [MenuController::class, 'edit'], [$authMiddleware]);
Route::post('/menus/:id/update', [MenuController::class, 'update'], [$authMiddleware]);

/** Rutas para Tasks */
Route::get('/tasks', [TaskController::class, 'index'], [$authMiddleware]);
Route::get('/tasks/create', [TaskController::class, 'create'], [$authMiddleware]);
Route::post('/tasks', [TaskController::class, 'store'], [$authMiddleware]);
Route::get('/tasks/wastebasket', [TaskController::class, 'wastebasket'], [$authMiddleware]);
Route::post('/tasks/:id/restore', [TaskController::class, 'restore'], [$authMiddleware]);
Route::post('/tasks/:id/destroy', [TaskController::class, 'destroy'], [$authMiddleware]);
Route::post('/tasks/:id/delete', [TaskController::class, 'delete'], [$authMiddleware]);
Route::post('/tasks/:id/edit', [TaskController::class, 'edit'], [$authMiddleware]);
Route::post('/tasks/:id/update', [TaskController::class, 'update'], [$authMiddleware]);
Route::post('/tasks/:id/update_done', [TaskController::class, 'update_done'], [$authMiddleware]);

/** Rutas para Institución */
Route::get('/institucion', [InstitucionController::class, 'index'], [$authMiddleware]);
Route::post('/institucion/update', [InstitucionController::class, 'update'], [$authMiddleware]);

/** Rutas para Ofertas Educativas */

Route::dispatch();
