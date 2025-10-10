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
        $nombre = preg_replace('/\s+/', ' ', trim($_POST['nombre']));
        $es_bachillerato = trim($_POST['es_bachillerato']);

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $datos = [
            'nombre' => $nombre,
            'es_bachillerato' => $es_bachillerato
        ];

        if ($this->subNivelEducacionModelo->existeNombre($nombre)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Subnivel de Educación [$nombre] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->subNivelEducacionModelo->insertar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "El Subnivel de Educación fue insertado exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "El Subnivel de Educación no fue insertado exitosamente. Error: " . $ex->getMessage();
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
        $subnivelActual = $this->subNivelEducacionModelo->obtenerSubnivel($id);

        $datos = [
            'titulo' => 'Editar Perfil',
            'dashboard' => 'Admin',
            'subnivel' => $subnivelActual,
            'nombreVista' => 'admin/subnivel_educacion/edit.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function update()
    {
        $id = $_POST['id_nivel_educacion'];
        $nombre = preg_replace('/\s+/', ' ', trim($_POST['nombre']));
        $es_bachillerato = trim($_POST['es_bachillerato']);

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $datos = [
            'id_nivel_educacion' => $id,
            'nombre' => $nombre,
            'es_bachillerato' => $es_bachillerato
        ];

        $subnivelActual = $this->subNivelEducacionModelo->obtenerSubnivel($id);

        if ($subnivelActual->nombre != $nombre && $this->subNivelEducacionModelo->existeNombre($nombre)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Subnivel de Educación [$nombre] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->subNivelEducacionModelo->actualizar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "El Subnivel de Educación fue actualizado exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "El Subnivel de Educación no fue actualizado exitosamente. Error: " . $ex->getMessage();
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
            $this->subNivelEducacionModelo->eliminar($id);
            // Mensaje de éxito
            $_SESSION['mensaje'] = "Subnivel de Educación eliminado exitosamente de la base de datos.";
            $_SESSION['tipo'] = "success";
            $_SESSION['icono'] = "check";
        } catch (PDOException $e) {
            $_SESSION['mensaje'] = "El Subnivel de Educación no fue eliminado exitosamente. Error: " . $e->getMessage();
            $_SESSION['tipo'] = "danger";
            $_SESSION['icono'] = "ban";
        }
        redireccionar('subniveles_educacion');
    }
}
