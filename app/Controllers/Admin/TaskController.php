<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;

use App\Models\Admin\Task;

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

    public function update_done(int $id)
    {
        header('Content-Type: application/json');

        $input = $_POST ?? [];

        // Validar y convertir el string "false" a un booleano real de PHP
        $doneValue = isset($input['done']) ? filter_var($input['done'], FILTER_VALIDATE_BOOLEAN) : false;

        try {
            $this->taskModel->beginTransaction();

            // Pasar la variable ya convertida a booleano
            $this->taskModel->update_done($id, $doneValue);

            $this->taskModel->commit();

            // Es obligatorio usar echo para que JavaScript reciba el JSON
            echo json_encode([
                'ok' => true,
                'mensaje' => 'Tarea procesada con éxito.'
            ]);
            exit; // Detiene la ejecución para evitar código basura en la respuesta

        } catch (\Throwable $e) {
            $this->taskModel->rollBack();

            echo json_encode([
                'ok' => false,
                'mensaje' => "Ocurrió un error inesperado: " . $e->getMessage()
            ]);
            exit;
        }
    }

    public function delete(int $id)
    {
        header('Content-Type: application/json');

        try {
            $eliminado = $this->taskModel->delete($id);

            if ($eliminado) {
                return json_encode([
                    'success' => true,
                    'message' => 'El registro ha sido eliminado correctamente.'
                ]);
            } else {
                return json_encode([
                    'success' => false,
                    'message' => 'No se encontró el registro o ya fue eliminado.'
                ]);
            }
        } catch (\Throwable $e) {
            return json_encode([
                'success' => false,
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ]);
        }
    }

    // Método para restaurar una tarea (Botón Verde)
    public function restore(int $id)
    {
        header('Content-Type: application/json');
        try {
            // Llama al método restore() que añadimos en la clase Model
            $restaurado = $this->taskModel->restore($id);

            if ($restaurado) {
                echo json_encode(['success' => true, 'message' => 'La Tarea ha sido restaurada con éxito.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo restaurar la tarea.']);
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
            // 1. Buscamos a la en la base de datos antes de borrarlo
            $tarea = $this->taskModel->withTrashed()->find($id);

            if (!$tarea) {
                echo json_encode([
                    'success' => false,
                    'titulo'  => 'Atención',
                    'mensaje' => 'La Tarea no existe en el sistema.',
                    'estado'  => 'warning'
                ]);
                exit;
            }

            // 2. Ejecutamos la eliminación física definitiva en la base de datos
            $resultado = $this->taskModel->forceDelete($id);

            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'titulo'  => '¡Completado!',
                    'mensaje' => 'La Tarea ha sido eliminada permanentemente del sistema.',
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
            // CAPTURA EXITOSA: Ahora que removimos el die(), el catch atrapa el error 1451 perfectamente
            if ($e->getCode() === 1451) {
                echo json_encode([
                    'success' => false,
                    'titulo'  => 'No se puede eliminar',
                    'mensaje' => 'La Tarea tiene registros vinculados. Debe reasignar o borrar esas dependencias antes de eliminarla de forma definitiva.',
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

    public function wastebasket()
    {
        $title = "Papelera de Tareas";
        $search = isset($_GET['search']) ? $_GET['search'] : "";

        // 1. Iniciamos el filtro de borrados lógicos
        $query = $this->taskModel->onlyTrashed();

        if ($search !== "") {
            // Obtenemos dinámicamente el nombre de la tabla del modelo (ej: usuarios)
            $table = $this->taskModel->getTable() ?? 'sw_tarea'; // Asegura tener un método getTable o pon el nombre de tu tabla directamente

            // 2. Asignamos el WHERE explícito calificando las columnas con su tabla
            $query->where = "({$table}.tarea LIKE ?)";

            $query->values = [
                '%' . $search . '%'
            ];
        }

        // 3. Ordenamos y paginamos de forma nativa
        $tasks = $query->orderBy('deleted_at', 'DESC')->paginate(5);

        // 4. Renderizamos la vista de la papelera
        return $this->view('admin.tasks.wastebasket', compact('tasks', 'title'));
    }
}
