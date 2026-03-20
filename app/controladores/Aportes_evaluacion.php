<?php
class Aportes_evaluacion extends Controlador
{
    private $tipoAporteModelo;
    private $aporteEvaluacionModelo;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        $this->tipoAporteModelo = $this->modelo('Tipo_aporte');
        $this->aporteEvaluacionModelo = $this->modelo('Aporte_evaluacion');
    }

    public function index()
    {
        $datos = [
            'titulo' => 'Aportes de Evaluación',
            'dashboard' => 'Admin',
            'nombreVista' => 'admin/aportes_evaluacion/index.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function create()
    {
        $tipos_aporte = $this->tipoAporteModelo->obtenerTodos();
        $datos = [
            'titulo' => 'Crear Aporte de Evaluación',
            'dashboard' => 'Admin',
            'tipos_aporte' => $tipos_aporte,
            'nombreVista' => 'admin/aportes_evaluacion/create.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function store()
    {
        $id_tipo_aporte = $_POST['tipos_aporte'];
        $nombre = strtoupper(preg_replace('/\s+/', ' ', trim($_POST['nombre'])));
        $abreviatura = strtoupper(trim($_POST['abreviatura']));
        // $ponderacion = $_POST['ponderacion'];
        $descripcion = $_POST['descripcion'] ? strtoupper(preg_replace('/\s+/', ' ', trim($_POST['descripcion']))) : null;

        $datos = [
            'id_tipo_aporte' => $id_tipo_aporte,
            'ap_nombre' => $nombre,
            'ap_abreviatura' => $abreviatura,
            // 'ap_ponderacion' => $ponderacion,
            'ap_descripcion' => $descripcion
        ];

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        if ($this->aporteEvaluacionModelo->existeCampo('ap_nombre', $nombre)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Aporte de Evaluación \"$nombre\" en la base de datos.";
            $tipo_mensaje = "error";
        } else if ($this->aporteEvaluacionModelo->existeCampo('ap_abreviatura', $abreviatura)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe la abreviatura del Aporte de Evaluación \"$abreviatura\" en la base de datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->aporteEvaluacionModelo->insertar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "El Aporte de Evaluación fue insertado exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "El Aporte de Evaluación no fue insertado exitosamente. Error: " . $ex->getMessage();
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
        $aporteActual = $this->aporteEvaluacionModelo->obtener($id);
        $tipos_aporte = $this->tipoAporteModelo->obtenerTodos();

        $datos = [
            'titulo' => 'Editar Aporte de Evaluación',
            'dashboard' => 'Admin',
            'aporte' => $aporteActual,
            'tipos_aporte' => $tipos_aporte,
            'nombreVista' => 'admin/aportes_evaluacion/edit.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function update()
    {
        $id_aporte_evaluacion = $_POST['id_aporte_evaluacion'];
        $aporteActual = $this->aporteEvaluacionModelo->obtener($id_aporte_evaluacion);

        $ap_nombre = strtoupper(preg_replace('/\s+/', ' ', trim($_POST['nombre'])));
        $ap_abreviatura = strtoupper(trim($_POST['abreviatura']));
        $ap_descripcion = $_POST['descripcion'] ? strtoupper(preg_replace('/\s+/', ' ', trim($_POST['descripcion']))) : null;

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $datos = [
            'id_aporte_evaluacion' => $id_aporte_evaluacion,
            'ap_nombre' => $ap_nombre,
            'ap_abreviatura' => $ap_abreviatura,
            'ap_descripcion' => $ap_descripcion
        ];

        if ($aporteActual->ap_nombre != $ap_nombre && $this->aporteEvaluacionModelo->existeNombre($ap_nombre)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Aporte de Evaluación [$ap_nombre] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else if ($aporteActual->ap_abreviatura != $ap_abreviatura && $this->aporteEvaluacionModelo->existeCampo('ap_abreviatura', $ap_abreviatura)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe la abreviatura del Aporte de Evaluación \"$ap_abreviatura\" en la base de datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->aporteEvaluacionModelo->actualizar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "El Aporte de Evaluación fue actualizado exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "El Aporte de Evaluación no fue actualizado exitosamente. Error: " . $ex->getMessage();
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
            $this->aporteEvaluacionModelo->eliminar($id);
            // Mensaje de éxito
            $_SESSION['mensaje'] = "Aporte de Evaluación eliminado exitosamente de la base de datos.";
            $_SESSION['tipo'] = "success";
            $_SESSION['icono'] = "check";
        } catch (PDOException $e) {
            $_SESSION['mensaje'] = "El Aporte de Evaluación no fue eliminado exitosamente ya que está siendo utilizado en otras partes del sistema.";
            $_SESSION['tipo'] = "danger";
            $_SESSION['icono'] = "ban";
        }
        redireccionar('aportes_evaluacion');
    }
}