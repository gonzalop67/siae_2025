<?php

namespace App\Controllers;

use App\Models\Permiso;

class PermissionController extends Controller
{
    protected Permiso $permissionModel;

    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->permissionModel = new Permiso;
    }

    public function index()
    {
        $title = 'Permisos | ' . APP_NAME;
        $search = trim($_GET['search'] ?? '');

        if ($search !== '') {
            // 1. Creamos la estructura SQL agrupada con paréntesis para proteger la lógica
            $this->permissionModel->where = "(nombre LIKE ? OR slug LIKE ? OR descripcion LIKE ?)";

            // 2. Preparamos los comodines de forma segura
            $term = "%{$search}%";

            // 3. Pasamos los valores al arreglo que procesará el prepare del ORM
            $this->permissionModel->values = [$term, $term, $term];
        }

        // El ORM inyectará de forma automática el ORDER BY y resolverá la paginación
        $permissions = $this->permissionModel
            ->orderBy('nombre')
            ->paginate(5);

        // return $permissions;

        return $this->view('admin.permissions.index', compact('permissions', 'title'));
    }

    public function create()
    {
        $title = "Crear Permiso";

        return $this->view('admin.permissions.create', compact('title'));
    }

    public function store()
    {
        // Indicar al navegador/JS que la respuesta siempre será un JSON
        header('Content-Type: application/json');

        // 1. Capturar datos directamente de $_POST (compatible al 100% con FormData de JS)
        $input = $_POST ?? [];

        // 2. Validar datos de entrada
        if (!$this->permissionModel->validate($input)) {
            return json_encode([
                'ok' => false,
                'errors' => $this->permissionModel->errors
            ]);
        }

        // 3. Preparación del set de datos (limpiando espacios)
        $datos = [
            'nombre' => trim($input['nombre'] ?? ''),
            'slug'   => trim($input['slug'] ?? ''),
            'descripcion'   => trim($input['descripcion'] ?? ''),
        ];

        // 4. Persistencia con manejo de transacciones
        try {
            // Iniciar transacción SQL
            $this->permissionModel->beginTransaction();

            // Ejecutamos la creación en la base de datos
            $this->permissionModel->create($datos);

            // Confirmar cambios si todo salió bien
            $this->permissionModel->commit();

            return json_encode([
                'ok' => true,
                'mensaje' => 'Permiso procesado con éxito.'
            ]);
        } catch (\Throwable $e) {
            // Revertir transacción SQL ante cualquier fallo
            $this->permissionModel->rollBack();

            return json_encode([
                'ok' => false,
                'mensaje' => "Ocurrió un error inesperado: " . $e->getMessage()
            ]);
        }
    }

    public function edit(int $id)
    {
        $permission = $this->permissionModel->find($id);
        $title = "Editar Permiso";

        return $this->view('admin.permissions.edit', compact('title', 'permission'));
    }

    public function update(int $id)
    {
        // Indicar al navegador/JS que la respuesta siempre será un JSON
        header('Content-Type: application/json');

        // 1. Capturar datos directamente de $_POST (compatible al 100% con FormData de JS)
        $input = $_POST ?? [];

        // 2. Validar datos de entrada
        if (!$this->permissionModel->validate($input, $id)) {
            return json_encode([
                'ok' => false,
                'errors' => $this->permissionModel->errors
            ]);
        }

        // 3. Preparación del set de datos (limpiando espacios)
        $datos = [
            'nombre' => trim($input['nombre'] ?? ''),
            'slug'   => trim($input['slug'] ?? ''),
            'descripcion'   => trim($input['descripcion'] ?? ''),
        ];

        // 4. Persistencia con manejo de transacciones atómicas
        try {
            $this->permissionModel->beginTransaction();
            // echo "<pre>"; print_r($datos); echo "</pre>"; die();

            // Ejecutar actualización
            $this->permissionModel->update($id, $datos);

            // Confirmar cambios en la base de datos
            $this->permissionModel->commit();

            return json_encode([
                'ok' => true,
                'mensaje' => 'Permiso procesado con éxito.'
            ]);
        } catch (\Throwable $e) {
            // Deshace cualquier cambio si algo falla en el proceso
            $this->permissionModel->rollBack();

            return json_encode([
                'ok' => false,
                'mensaje' => "Ocurrió un error inesperado: " . $e->getMessage()
            ]);
        }
    }
}
