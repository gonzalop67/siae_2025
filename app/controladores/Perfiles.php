<?php
class Perfiles extends Controlador
{
    private $perfilModelo;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        $this->perfilModelo = $this->modelo('Perfil');
    }

    public function index()
    {
        $perfiles = $this->perfilModelo->obtenerPerfiles();
        $datos = [
            'titulo' => 'Perfiles CRUD',
            'dashboard' => 'Admin',
            'perfiles' => $perfiles,
            'nombreVista' => 'admin/perfiles/index.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function create()
    {
        $datos = [
            'titulo' => 'Crear Perfiles',
            'dashboard' => 'Admin',
            'nombreVista' => 'admin/perfiles/create.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function insert()
    {
        $pe_nombre = preg_replace('/\s+/', ' ', trim($_POST['nombre']));
        $pe_slug = preg_replace('/\s+/', ' ', trim($_POST['slug']));

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $datos = [ 
            'pe_nombre' => $pe_nombre,
            'pe_slug' => $pe_slug
        ];

        if ($this->perfilModelo->existeNombrePerfil($pe_nombre)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Perfil [$pe_nombre] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else if ($this->perfilModelo->existeSlugPerfil($pe_slug)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el slug de Perfil [$pe_slug] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->perfilModelo->insertar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "El Perfil fue insertado exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "El Perfil no fue insertado exitosamente. Error: " . $ex->getMessage();
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
        $perfilActual = $this->perfilModelo->obtenerPerfil($id);

        $datos = [
            'titulo' => 'Editar Perfil',
            'dashboard' => 'Admin',
            'perfil' => $perfilActual,
            'nombreVista' => 'admin/perfiles/edit.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function update()
    {
        $id_perfil = $_POST['id_perfil'];
        $perfilActual = $this->perfilModelo->obtenerPerfil($id_perfil);

        $pe_nombre = preg_replace('/\s+/', ' ', trim($_POST['nombre']));
        $pe_slug = preg_replace('/\s+/', ' ', trim($_POST['slug']));

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $datos = [
            'id_perfil' => $id_perfil, 
            'pe_nombre' => $pe_nombre,
            'pe_slug' => $pe_slug
        ];

        if ($perfilActual->pe_nombre != $pe_nombre && $this->perfilModelo->existeNombrePerfil($pe_nombre)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Perfil [$pe_nombre] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else if ($perfilActual->pe_slug != $pe_slug && $this->perfilModelo->existeSlugPerfil($pe_slug)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el slug de Perfil [$pe_slug] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->perfilModelo->actualizar($datos);
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
            $this->perfilModelo->eliminar($id);
            // Mensaje de éxito
            $_SESSION['mensaje'] = "Perfil eliminado exitosamente de la base de datos.";
            $_SESSION['tipo'] = "success";
            $_SESSION['icono'] = "check";
        } catch (PDOException $e) {
            $_SESSION['mensaje'] = "El Perfil no fue eliminado exitosamente. Error: " . $e->getMessage();
            $_SESSION['tipo'] = "danger";
            $_SESSION['icono'] = "ban";
        }
        redireccionar('perfiles');
    }
}
