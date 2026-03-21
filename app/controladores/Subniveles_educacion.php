<?php
class Subniveles_educacion extends Controlador
{
    private $nivelEducacionModelo;
    private $subNivelEducacionModelo;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        $this->nivelEducacionModelo = $this->modelo('Nivel_educacion');
        $this->subNivelEducacionModelo = $this->modelo('Subnivel_educacion');
    }

    public function index()
    {
        $subniveles_educacion = $this->subNivelEducacionModelo->obtenerSubniveles();
        $datos = [
            'titulo' => 'CRUD Sub Nivel de Educación',
            'dashboard' => 'Admin',
            'subniveles_educacion' => $subniveles_educacion,
            'nombreVista' => 'admin/subnivel_educacion/index.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function create()
    {
        $niveles_educacion = $this->nivelEducacionModelo->obtenerNiveles();
        $datos = [
            'titulo' => 'Crear Subnivel de Educación',
            'dashboard' => 'Admin',
            'niveles_educacion' => $niveles_educacion,
            'nombreVista' => 'admin/subnivel_educacion/create.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function store()
    {
        $nivel_id = trim($_POST['nivel_id']);
        $nombre = preg_replace('/\s+/', ' ', trim($_POST['nombre']));
        $slug = trim($_POST['slug']);
        $es_bachillerato = trim($_POST['es_bachillerato']);

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $datos = [
            'nivel_id' => $nivel_id,
            'nombre' => $nombre,
            'slug' => $slug,
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
        $niveles_educacion = $this->nivelEducacionModelo->obtenerNiveles();

        $datos = [
            'titulo' => 'Editar Perfil',
            'dashboard' => 'Admin',
            'subnivel' => $subnivelActual,
            'niveles_educacion' => $niveles_educacion,
            'nombreVista' => 'admin/subnivel_educacion/edit.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function update()
    {
        $id = $_POST['id_nivel_educacion'];
        $nivel_id = trim($_POST['nivel_id']);
        $nombre = preg_replace('/\s+/', ' ', trim($_POST['nombre']));
        $slug = trim($_POST['slug']);
        $es_bachillerato = trim($_POST['es_bachillerato']);

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $datos = [
            'id' => $id,
            'nivel_id' => $nivel_id,
            'nombre' => $nombre,
            'slug' => $slug,
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
