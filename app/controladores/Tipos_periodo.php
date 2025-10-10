<?php
class Tipos_periodo extends Controlador
{
    private $tipoPeriodoModelo;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        $this->tipoPeriodoModelo = $this->modelo('Tipo_periodo');
    }

    public function index()
    {
        $tipos_periodo = $this->tipoPeriodoModelo->obtenerTodos();
        $datos = [
            'titulo' => 'CRUD Tipos Periodo de Evaluación',
            'dashboard' => 'Admin',
            'tipos_periodo' => $tipos_periodo,
            'nombreVista' => 'admin/tipos_periodo/index.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function create()
    {
        $datos = [
            'titulo' => 'Crear Tipos Periodo de Evaluación',
            'dashboard' => 'Admin',
            'nombreVista' => 'admin/tipos_periodo/create.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function store()
    {
        $nombre = preg_replace('/\s+/', ' ', strtoupper(trim($_POST['nombre'])));
        $slug = preg_replace('/\s+/', ' ', strtolower(trim($_POST['slug'])));

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $datos = [ 
            'tp_descripcion' => $nombre,
            'tp_slug' => $slug
        ];

        if ($this->tipoPeriodoModelo->existeCampo('tp_descripcion', $nombre)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Perfil [$nombre] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else if ($this->tipoPeriodoModelo->existeCampo('tp_slug', $slug)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el slug de Perfil [$slug] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->tipoPeriodoModelo->insertar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "El Tipo de Periodo de Evaluación fue insertado exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "El Tipo de Periodo de Evaluación no fue insertado exitosamente. Error: " . $ex->getMessage();
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
        $tipoPeriodoActual = $this->tipoPeriodoModelo->obtener($id);

        $datos = [
            'titulo' => 'Editar Tipo de Periodo',
            'dashboard' => 'Admin',
            'tipo_periodo' => $tipoPeriodoActual,
            'nombreVista' => 'admin/tipos_periodo/edit.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function update()
    {
        $id_tipo_periodo = $_POST['id_tipo_periodo'];
        $tipoPeriodoActual = $this->tipoPeriodoModelo->obtener($id_tipo_periodo);

        $tp_descripcion = preg_replace('/\s+/', ' ', strtoupper(trim($_POST['nombre'])));
        $tp_slug = preg_replace('/\s+/', ' ', strtolower(trim($_POST['slug'])));

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $datos = [
            'id_tipo_periodo' => $id_tipo_periodo, 
            'tp_descripcion' => $tp_descripcion,
            'tp_slug' => $tp_slug
        ];

        if ($tipoPeriodoActual->tp_descripcion != $tp_descripcion && $this->tipoPeriodoModelo->existeCampo('tp_descripcion', $tp_descripcion)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Tipo de Periodo [$tp_descripcion] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else if ($tipoPeriodoActual->tp_slug != $tp_slug && $this->tipoPeriodoModelo->existeCampo('tp_slug', $tp_slug)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el slug de Tipo de Periodo [$tp_slug] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->tipoPeriodoModelo->actualizar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "El Usuario fue actualizado exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "El Usuario no fue insertado exitosamente. Error: " . $ex->getMessage();
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
            $this->tipoPeriodoModelo->eliminar($id);
            // Mensaje de éxito
            $_SESSION['mensaje'] = "Tipo de Periodo de Evaluación eliminado exitosamente de la base de datos.";
            $_SESSION['tipo'] = "success";
            $_SESSION['icono'] = "check";
        } catch (PDOException $e) {
            $_SESSION['mensaje'] = "El Tipo de Periodo de Evaluación no fue eliminado exitosamente. Error: " . $e->getMessage();
            $_SESSION['tipo'] = "danger";
            $_SESSION['icono'] = "ban";
        }
        redireccionar('tipos_periodo');
    }
}