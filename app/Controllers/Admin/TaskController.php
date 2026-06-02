<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;

use App\Models\Task;

class TaskController extends Controller
{
    protected Task $taskModel;

    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->taskModel = new Task;
    }

    /**
     * Muestra el listado del recurso.
     */
    public function index()
    {
        $title = 'Tareas | ' . APP_NAME;

        $search = isset($_GET['search']) ? $_GET['search'] : "";

        if ($search !== "") {
            $tasks = $this->taskModel
                ->where('tarea', 'LIKE', '%' . $_GET['search'] . '%')
                ->orderBy('fecha', 'DESC')
                ->paginate(5);
        } else {
            $tasks = $this->taskModel
                ->orderBy('fecha', 'DESC')
                ->paginate(5);
        }
        
        return $this->view('admin.tasks.index', compact('tasks', 'title'));
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     */
    public function create()
    {
        $title = 'Crear Tarea';
        return $this->view('admin.tasks.create', compact('title'));
    }

    /**
     * Almacena un recurso recién creado en la base de datos.
     */
    public function store()
    {
        // Indicar al navegador/JS que la respuesta siempre será un JSON
        header('Content-Type: application/json');

        // 1. Capturar datos directamente de $_POST (compatible al 100% con FormData de JS)
        $input = $_POST ?? [];

        // 2. Validar datos de entrada
        if (!$this->taskModel->validate($input)) {
            return json_encode([
                'ok' => false,
                'errors' => $this->taskModel->errors
            ]);
        }

        // 3. Preparación del set de datos (limpiando espacios)
        $datos = [
            'tarea' => trim($input['tarea'] ?? ''),
        ];

        // 4. Persistencia con manejo de transacciones
        try {
            // Iniciar transacción SQL
            $this->taskModel->beginTransaction();

            // Ejecutamos la creación en la base de datos
            $this->taskModel->create($datos);

            // Confirmar cambios si todo salió bien
            $this->taskModel->commit();

            return json_encode([
                'ok' => true,
                'mensaje' => 'Tarea procesada con éxito.'
            ]);
        } catch (\Throwable $e) {
            // Revertir transacción SQL ante cualquier fallo
            $this->taskModel->rollBack();

            return json_encode([
                'ok' => false,
                'mensaje' => "Ocurrió un error inesperado: " . $e->getMessage()
            ]);
        }
    }

    /**
     * Muestra el formulario para editar un recurso específico.
     */
    public function edit(int $id)
    {
        $task = $this->taskModel->find($id);
        return $task;
    }

    /**
     * Actualiza un recurso específico en la base de datos.
     */
    public function update(int $id)
    {
        // Indicar al navegador/JS que la respuesta siempre será un JSON
        header('Content-Type: application/json');

        // 1. Capturar datos directamente de $_POST (compatible al 100% con FormData de JS)
        $input = $_POST ?? [];

        // 2. Validar datos de entrada
        if (!$this->taskModel->validate($input, $id)) {
            return json_encode([
                'ok' => false,
                'errors' => $this->taskModel->errors
            ]);
        }

        // 3. Preparación del set de datos (limpiando espacios)
        $datos = [
            'tarea' => trim($input['tarea'] ?? ''),
        ];

        // 4. Persistencia con manejo de transacciones atómicas
        try {
            $this->taskModel->beginTransaction();
            // echo "<pre>"; print_r($datos); echo "</pre>"; die();

            // Ejecutar actualización
            $this->taskModel->update($id, $datos);

            // Confirmar cambios en la base de datos
            $this->taskModel->commit();

            return json_encode([
                'ok' => true,
                'mensaje' => 'Tarea procesada con éxito.'
            ]);
        } catch (\Throwable $e) {
            // Deshace cualquier cambio si algo falla en el proceso
            $this->taskModel->rollBack();

            return json_encode([
                'ok' => false,
                'mensaje' => "Ocurrió un error inesperado: " . $e->getMessage()
            ]);
        }
    }

    /**
     * Elimina un recurso específico de la base de datos.
     */
    public function destroy($id)
    {
        // $this->model->delete($id);
        // return redirect('/task');
    }
}
