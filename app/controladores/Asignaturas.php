<?php
class Asignaturas extends Controlador
{
    private $areaModelo;
    private $asignaturaModelo;
    private $tipoAsignaturaModelo;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        $this->areaModelo = $this->modelo('Area');
        $this->asignaturaModelo = $this->modelo('Asignatura');
        $this->tipoAsignaturaModelo = $this->modelo('Tipo_asignatura');
    }

    public function index()
    {
        $asignaturas = $this->asignaturaModelo->obtenerAsignaturas();
        $datos = [
            'titulo' => 'Asignaturas',
            'asignaturas' => $asignaturas,
            'dashboard' => 'Admin',
            'nombreVista' => 'admin/asignaturas/index.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function create()
    {
        $areas = $this->areaModelo->obtenerAreas();
        $tipos_asignatura = $this->tipoAsignaturaModelo->obtenerTodos();
        $datos = [
            'titulo' => 'Crear Nueva Asignatura',
            'dashboard' => 'Admin',
            'areas' => $areas,
            'tipos_asignatura' => $tipos_asignatura,
            'nombreVista' => 'admin/asignaturas/create.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function store()
    {
        $id_area = $_POST['areas'];
        $nombre = strtoupper(preg_replace('/\s+/', ' ', trim($_POST['nombre'])));
        $abreviatura = strtoupper(trim($_POST['abreviatura']));
        $id_tipo_asignatura = $_POST['tipos_asignatura'];

        $datos = [
            'id_area' => $id_area,
            'as_nombre' => $nombre,
            'as_abreviatura' => $abreviatura,
            'id_tipo_asignatura' => $id_tipo_asignatura
        ];

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        if ($this->asignaturaModelo->existeCampo('as_nombre', $nombre)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe la Asignatura \"$nombre\" en la base de datos.";
            $tipo_mensaje = "error";
        } else if ($this->asignaturaModelo->existeCampo('as_abreviatura', $abreviatura)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe la abreviatura de Asignatura \"$abreviatura\" en la base de datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->asignaturaModelo->insertar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "La Asignatura fue insertada exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "La Asignatura no fue insertada exitosamente. Error: " . $ex->getMessage();
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
        $areas = $this->areaModelo->obtenerAreas();
        $asignaturaActual = $this->asignaturaModelo->obtener($id);
        $tipos_asignatura = $this->tipoAsignaturaModelo->obtenerTodos();

        $datos = [
            'titulo' => 'Editar Área',
            'dashboard' => 'Admin',
            'areas' => $areas,
            'asignatura' => $asignaturaActual,
            'tipos_asignatura' => $tipos_asignatura,
            'nombreVista' => 'admin/asignaturas/edit.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function update()
    {
        $id_asignatura = $_POST['id_asignatura'];
        $id_area = $_POST['areas'];
        $nombre = strtoupper(preg_replace('/\s+/', ' ', trim($_POST['nombre'])));
        $abreviatura = strtoupper(trim($_POST['abreviatura']));
        $id_tipo_asignatura = $_POST['tipos_asignatura'];

        $datos = [
            'id_asignatura' => $id_asignatura,
            'id_area' => $id_area,
            'as_nombre' => $nombre,
            'as_abreviatura' => $abreviatura,
            'id_tipo_asignatura' => $id_tipo_asignatura
        ];

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $asignaturaActual = $this->asignaturaModelo->obtener($id_asignatura);

        if ($asignaturaActual->as_nombre != $nombre && $this->asignaturaModelo->existeCampo('as_nombre', $nombre)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe la Asignatura \"$nombre\" en la base de datos.";
            $tipo_mensaje = "error";
        } else if ($asignaturaActual->as_abreviatura != $abreviatura && $this->asignaturaModelo->existeCampo('as_abreviatura', $abreviatura)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe la abreviatura de Asignatura \"$abreviatura\" en la base de datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->asignaturaModelo->actualizar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "La Asignatura fue insertada exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "La Asignatura no fue insertada exitosamente. Error: " . $ex->getMessage();
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
            $this->asignaturaModelo->eliminar($id);
            // Mensaje de éxito
            $_SESSION['mensaje'] = "Asignatura eliminada exitosamente de la base de datos.";
            $_SESSION['tipo'] = "success";
            $_SESSION['icono'] = "check";
        } catch (PDOException $e) {
            $_SESSION['mensaje'] = "La Asignatura no fue eliminada exitosamente. Error: " . $e->getMessage();
            $_SESSION['tipo'] = "danger";
            $_SESSION['icono'] = "ban";
        }
        redireccionar('asignaturas');
    }
}