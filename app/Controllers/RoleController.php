<?php

namespace App\Controllers;

use App\Models\Perfil;

class RoleController extends Controller
{
    protected Perfil $roleModel;

    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->roleModel = new Perfil;
    }

    public function index()
    {
        $title = "Roles Admin";

        $search = isset($_GET['search']) ? $_GET['search'] : "";

        if ($search !== "") {
            $roles = $this->roleModel
                ->where('pe_nombre', 'LIKE', '%' . $_GET['search'] . '%')
                ->orWhere('pe_slug', 'LIKE', '%' . $_GET['search'] . '%')
                ->orderBy('pe_nombre')
                ->paginate(5);
        } else {
            $roles = $this->roleModel
                ->orderBy('pe_nombre')
                ->paginate(5);
        }

        // return $roles;

        return $this->view('admin.roles.index', compact('roles', 'title'));
    }

    public function create()
    {
        $title = "Nuevo Perfil";

        return $this->view('admin.roles.create', compact('title'));
    }

    public function store()
    {
        // Indicar al navegador/JS que la respuesta siempre será un JSON
        header('Content-Type: application/json');

        // 1. Capturar datos directamente de $_POST (compatible al 100% con FormData de JS)
        $input = $_POST ?? [];

        // 2. Validar datos de entrada
        if (!$this->roleModel->validate($input)) {
            return json_encode([
                'ok' => false,
                'errors' => $this->roleModel->errors
            ]);
        }

        // 3. Preparación del set de datos (limpiando espacios)
        $datos = [
            'pe_nombre' => trim($input['nombre'] ?? ''),
            'pe_slug'   => trim($input['slug'] ?? ''),
        ];

        // 4. Persistencia con manejo de transacciones
        try {
            // Iniciar transacción SQL
            $this->roleModel->beginTransaction();

            // Ejecutamos la creación en la base de datos
            $this->roleModel->create($datos);

            // Confirmar cambios si todo salió bien
            $this->roleModel->commit();

            return json_encode([
                'ok' => true,
                'mensaje' => 'Rol procesado con éxito.'
            ]);
        } catch (\Throwable $e) {
            // Revertir transacción SQL ante cualquier fallo
            $this->roleModel->rollBack();

            return json_encode([
                'ok' => false,
                'mensaje' => "Ocurrió un error inesperado: " . $e->getMessage()
            ]);
        }
    }
}
