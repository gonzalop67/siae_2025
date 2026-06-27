<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;

use App\Models\Admin\Permiso;

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

    public function delete(int $id)
    {
        header('Content-Type: application/json');

        try {
            $eliminado = $this->permissionModel->delete($id);

            if ($eliminado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'El registro ha sido eliminado correctamente.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'No se encontró el registro o ya fue eliminado.'
                ]);
            }
        } catch (\Throwable $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ]);
        }
        exit; // Detiene la ejecución para que solo devuelva el JSON
    }

    public function wastebasket()
    {
        $title = "Papelera de Permisos";
        $search = isset($_GET['search']) ? $_GET['search'] : "";

        // 1. Iniciamos el filtro de borrados lógicos
        $query = $this->permissionModel->onlyTrashed();

        if ($search !== "") {
            // Obtenemos dinámicamente el nombre de la tabla del modelo (ej: usuarios)
            $table = $this->permissionModel->getTable() ?? 'sw_permiso'; // Asegura tener un método getTable o pon el nombre de tu tabla directamente

            // 2. Asignamos el WHERE explícito calificando las columnas con su tabla
            $query->where = "({$table}.nombre LIKE ? OR {$table}.slug LIKE ? OR {$table}.descripcion LIKE ?)";

            $query->values = [
                '%' . $search . '%',
                '%' . $search . '%',
                '%' . $search . '%'
            ];
        }

        // 3. Ordenamos y paginamos de forma nativa
        $permisos = $query->orderBy('deleted_at', 'DESC')->paginate(5);

        // 4. Renderizamos la vista de la papelera
        return $this->view('admin.permissions.wastebasket', compact('permisos', 'title'));
    }

    // Método para restaurar un usuario (Botón Verde)
    public function restore(int $id)
    {
        header('Content-Type: application/json');
        try {
            // Llama al método restore() que añadimos en la clase Model
            $restaurado = $this->permissionModel->restore($id);

            if ($restaurado) {
                echo json_encode(['success' => true, 'message' => 'El permiso ha sido restaurado con éxito.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo restaurar el permiso.']);
            }
        } catch (\Throwable $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }

    // Método para eliminación física definitiva (Botón Rojo)
    public function destroy(int $id)
    {
        header('Content-Type: application/json');

        try {
            // 1. Buscamos al usuario en la base de datos antes de borrarlo
            $perfil = $this->permissionModel->withTrashed()->find($id);

            if (!$perfil) {
                echo json_encode([
                    'success' => false,
                    'titulo'  => 'Atención',
                    'mensaje' => 'El permiso no existe en el sistema.',
                    'estado'  => 'warning'
                ]);
                exit;
            }

            // 2. Ejecutamos la eliminación física definitiva en la base de datos
            $resultado = $this->permissionModel->forceDelete($id);

            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'titulo'  => '¡Completado!',
                    'mensaje' => 'El permiso ha sido eliminado permanentemente del sistema.',
                    'estado'  => 'success'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'titulo'  => 'Error',
                    'mensaje' => 'No se pudo eliminar el registro de la base de datos.',
                    'estado'  => 'error'
                ]);
            }
        } catch (\mysqli_sql_exception $e) {
            // CAPTURA EXITOSA: el catch atrapa el error 1451 (Claves foráneas) perfectamente
            if ($e->getCode() === 1451) {
                echo json_encode([
                    'success' => false,
                    'titulo'  => 'No se puede eliminar',
                    'mensaje' => 'El permiso tiene registros vinculados. Debe reasignar o borrar esas dependencias antes de eliminarlo de forma definitiva.',
                    'estado'  => 'error'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'titulo'  => 'Error de Base de Datos',
                    'mensaje' => 'Fallo en la consulta: ' . $e->getMessage(),
                    'estado'  => 'error'
                ]);
            }
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'titulo'  => 'Error inesperado',
                'mensaje' => $e->getMessage(),
                'estado'  => 'error'
            ]);
        }
        exit;
    }
}
