<?php
class Subperiodos_evaluacion extends Controlador
{
    private $tipoPeriodoModelo;
    private $subPeriodoEvaluacionModelo;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        $this->tipoPeriodoModelo = $this->modelo('Tipo_periodo');
        $this->subPeriodoEvaluacionModelo = $this->modelo('Subperiodo_evaluacion');
    }

    public function index()
    {
        $subperiodos_evaluacion = $this->subPeriodoEvaluacionModelo->obtenerTodos();
        $datos = [
            'titulo' => 'CRUD Sub Periodo de Evaluación',
            'dashboard' => 'Admin',
            'subperiodos_evaluacion' => $subperiodos_evaluacion,
            'nombreVista' => 'admin/subperiodo_evaluacion/index.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function create()
    {
        $tipos_periodo = $this->tipoPeriodoModelo->obtenerTodos();
        $datos = [
            'titulo' => 'Crear Subnivel de Educación',
            'dashboard' => 'Admin',
            'tipos_periodo' => $tipos_periodo,
            'nombreVista' => 'admin/subperiodo_evaluacion/create.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function store()
    {
        $nombre = preg_replace('/\s+/', ' ', strtoupper(trim($_POST['nombre'])));
        $abreviatura = preg_replace('/\s+/', ' ', strtoupper(trim($_POST['abreviatura'])));
        $tipo_periodo = trim($_POST['tipo_periodo']);

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $datos = [
            'pe_nombre' => $nombre,
            'pe_abreviatura' => $abreviatura,
            'id_tipo_periodo' => $tipo_periodo
        ];

        if ($this->subPeriodoEvaluacionModelo->existeCampo('pe_nombre', $nombre)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Subperiodo de Evaluación [$nombre] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else if ($this->subPeriodoEvaluacionModelo->existeCampo('pe_abreviatura', $abreviatura)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe la abreviatura de Subperiodo de Evaluación [$abreviatura] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->subPeriodoEvaluacionModelo->insertar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "El Subperiodo de Evaluación fue insertado exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "El Subperiodo de Evaluación no fue insertado exitosamente. Error: " . $ex->getMessage();
                $tipo_mensaje = "error";
            }
        }

        echo json_encode(array(
            'ok' => $ok,
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'tipo_mensaje' => $tipo_mensaje
        ));
    }

    public function edit($id)
    {
        $subPeriodoActual = $this->subPeriodoEvaluacionModelo->obtener($id);
        $tipos_periodo = $this->tipoPeriodoModelo->obtenerTodos();

        $datos = [
            'titulo' => 'Editar Subperiodo de Evaluación',
            'dashboard' => 'Admin',
            'subperiodo' => $subPeriodoActual,
            'tipos_periodo' => $tipos_periodo,
            'nombreVista' => 'admin/subperiodo_evaluacion/edit.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function update()
    {
        $id = trim($_POST['id_sub_periodo_evaluacion']);
        $nombre = preg_replace('/\s+/', ' ', strtoupper(trim($_POST['nombre'])));
        $abreviatura = preg_replace('/\s+/', ' ', strtoupper(trim($_POST['abreviatura'])));
        $tipo_periodo = trim($_POST['tipo_periodo']);

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $datos = [
            'id_sub_periodo_evaluacion' => $id,
            'pe_nombre' => $nombre,
            'pe_abreviatura' => $abreviatura,
            'id_tipo_periodo' => $tipo_periodo
        ];

        //
        // print_r("<pre>");
        // print_r($datos);
        // print_r("</pre>");
        // die();
        //

        $subPeriodoActual = $this->subPeriodoEvaluacionModelo->obtener($id);

        if ($subPeriodoActual->pe_nombre != $nombre && $this->subPeriodoEvaluacionModelo->existeCampo('pe_nombre', $nombre)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Subperiodo de Evaluación [$nombre] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else if ($subPeriodoActual->pe_abreviatura != $abreviatura && $this->subPeriodoEvaluacionModelo->existeCampo('pe_abreviatura', $abreviatura)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe la abreviatura de Subperiodo de Evaluación [$abreviatura] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->subPeriodoEvaluacionModelo->actualizar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "El Subperiodo de Evaluación fue actualizado exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "El Subperiodo de Evaluación no fue actualizado exitosamente. Error: " . $ex->getMessage();
                $tipo_mensaje = "error";
            }
        }

        echo json_encode(array(
            'ok' => $ok,
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'tipo_mensaje' => $tipo_mensaje
        ));
    }

    public function delete($id)
    {
        try {
            // Eliminar el registro de la base de datos
            $this->subPeriodoEvaluacionModelo->eliminar($id);
            // Mensaje de éxito
            $_SESSION['mensaje'] = "Subperiodo de Evaluación eliminado exitosamente de la base de datos.";
            $_SESSION['tipo'] = "success";
            $_SESSION['icono'] = "check";
        } catch (PDOException $e) {
            $_SESSION['mensaje'] = "El Subperiodo de Evaluación no fue eliminado exitosamente. Error: " . $e->getMessage();
            $_SESSION['tipo'] = "danger";
            $_SESSION['icono'] = "ban";
        }
        redireccionar('subperiodos_evaluacion');
    }
}
