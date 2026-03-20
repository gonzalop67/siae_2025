<?php
class Niveles_educacion extends Controlador
{
    private $nivelEducacionModelo;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        $this->nivelEducacionModelo = $this->modelo('Nivel_educacion');
    }

    public function index()
    {
        $niveles_educacion = $this->nivelEducacionModelo->obtenerNiveles();
        $datos = [
            'titulo' => 'CRUD Nivel de Educación',
            'dashboard' => 'Admin',
            'niveles_educacion' => $niveles_educacion,
            'nombreVista' => 'admin/nivel_educacion/index.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function create()
    {
        $datos = [
            'titulo' => 'Crear Nivel de Educación',
            'dashboard' => 'Admin',
            'nombreVista' => 'admin/nivel_educacion/create.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function store()
    {
        $nombre = preg_replace('/\s+/', ' ', trim($_POST['nombre']));
        $slug = trim($_POST['slug']);

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $datos = [
            'nombre' => $nombre,
            'slug' => $slug
        ];

        if ($this->nivelEducacionModelo->existeNombre($nombre)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Nivel de Educación [$nombre] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->nivelEducacionModelo->insertar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "El Nivel de Educación fue insertado exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "El Nivel de Educación no fue insertado exitosamente. Error: " . $ex->getMessage();
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
        $nivelEducacionActual = $this->nivelEducacionModelo->obtenerNivelEducacion($id);

        $datos = [
            'titulo' => 'Editar Nivel de Educación',
            'dashboard' => 'Admin',
            'nivel_educacion' => $nivelEducacionActual,
            'nombreVista' => 'admin/nivel_educacion/edit.php'
        ];

        $this->vista('admin/index', $datos);
    }

    public function update()
    {
        $id = $_POST['id_nivel_educacion'];
        $nombre = preg_replace('/\s+/', ' ', trim($_POST['nombre']));
        $slug = trim($_POST['slug']);

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $datos = [
            'id' => $id,
            'nombre' => $nombre,
            'slug' => $slug
        ];

        $nivelActual = $this->nivelEducacionModelo->obtenerNivelEducacion($id);

        if ($nivelActual->nombre != $nombre && $this->nivelEducacionModelo->existeNombre($nombre)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Nivel de Educación [$nombre] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->nivelEducacionModelo->actualizar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "El Nivel de Educación fue actualizado exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "El Nivel de Educación no fue actualizado exitosamente. Error: " . $ex->getMessage();
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
            $this->nivelEducacionModelo->eliminar($id);
            // Mensaje de éxito
            $_SESSION['mensaje'] = "Nivel de Educación eliminado exitosamente de la base de datos.";
            $_SESSION['tipo'] = "success";
            $_SESSION['icono'] = "check";
        } catch (PDOException $e) {
            $_SESSION['mensaje'] = "El Nivel de Educación no fue eliminado exitosamente. Error: " . $e->getMessage();
            $_SESSION['tipo'] = "danger";
            $_SESSION['icono'] = "ban";
        }
        redireccionar('niveles_educacion');
    }
}
