<?php
class Menus extends Controlador
{
    private $menuModelo;
    private $perfilModelo;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        $this->menuModelo = $this->modelo('Menu');
        $this->perfilModelo = $this->modelo('Perfil');
    }

    public function index()
    {
        $datos = [
            'titulo' => 'CRUD Menus',
            'dashboard' => 'Admin',
            'nombreVista' => 'admin/menus/index.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function create()
    {
        $perfiles = $this->perfilModelo->obtenerPerfiles();
        $datos = [
            'titulo' => 'Menús Crear',
            'dashboard' => 'Admin',
            'perfiles' => $perfiles,
            'nombreVista' => 'admin/menus/create.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function insert()
    {
        $mnu_texto = preg_replace('/\s+/', ' ', trim($_POST['texto']));
        $mnu_link = trim($_POST['enlace']);
        $mnu_icono = trim($_POST['icono']);
        $mnu_publicado = trim($_POST['publicado']);
        $id_perfil = trim($_POST['perfil']);

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $datos = [
            'mnu_texto' => $mnu_texto,
            'mnu_link' => $mnu_link,
            'mnu_icono' => $mnu_icono,
            'mnu_publicado' => $mnu_publicado,
            'perfiles' => $_POST['perfiles']
        ];

        try {
            $this->menuModelo->insertar($datos);
            $ok = true;
            $_SESSION['mensaje'] = "El Menú fue insertado exitosamente.";
            $_SESSION['tipo'] = "success";
            $_SESSION['icono'] = "check";
        } catch (PDOException $ex) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "El Menú no fue insertado exitosamente. Error: " . $ex->getMessage();
            $tipo_mensaje = "error";
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
        $menu = $this->menuModelo->obtenerMenuPorId($id);
        $perfiles = $this->perfilModelo->obtenerPerfiles();
        $perfilesMenu = $this->menuModelo->obtenerPerfilesMenu($id);
        $datos = [
            'titulo' => 'Editar Menú',
            'dashboard' => 'Admin',
            'menu' => $menu,
            'perfiles' => $perfiles,
            'perfilesMenu' => $perfilesMenu,
            'nombreVista' => 'admin/menus/edit.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function update()
    {
        $id_menu = $_POST['id_menu'];
        $mnu_texto = preg_replace('/\s+/', ' ', trim($_POST['texto']));
        $mnu_link = trim($_POST['enlace']);
        $mnu_icono = trim($_POST['icono']);
        $mnu_publicado = trim($_POST['publicado']);
        $id_perfil = trim($_POST['perfil']);

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $datos = [
            'id_menu' => $id_menu,
            'mnu_texto' => $mnu_texto,
            'mnu_link' => $mnu_link,
            'mnu_icono' => $mnu_icono,
            'mnu_publicado' => $mnu_publicado,
            'perfiles' => $_POST['perfiles']
        ];

        $menuActual = $this->menuModelo->obtenerMenuPorId($id_menu);

        try {
            $this->menuModelo->actualizar($datos);
            $ok = true;
            $_SESSION['mensaje'] = "El Menú fue actualizado exitosamente.";
            $_SESSION['tipo'] = "success";
            $_SESSION['icono'] = "check";
        } catch (PDOException $ex) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "El Menú no fue actualizado exitosamente. Error: " . $ex->getMessage();
            $tipo_mensaje = "error";
        }

        echo json_encode(array(
            'ok' => $ok,
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'tipo_mensaje' => $tipo_mensaje
        ));
    }

    public function guardarOrden()
    {
        $menus = $_POST['menu'];
        echo $this->menuModelo->guardarOrden($menus);
    }

    public function delete($id)
    {
        $hijos = $this->menuModelo->listarMenusHijos($id);
        if (!empty($hijos)) {
            $_SESSION['mensaje'] = "El Menú no puede ser eliminado porque tiene recursos que están utilizándolo.";
            $_SESSION['tipo'] = "danger";
            $_SESSION['icono'] = "ban";
        } else {
            try {
                // Eliminar el registro de la base de datos
                $this->menuModelo->eliminar($id);
                // Mensaje de éxito
                $_SESSION['mensaje'] = "Menú eliminado exitosamente de la base de datos.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $e) {
                $_SESSION['mensaje'] = "El Menú no fue eliminado exitosamente. Error: " . $e->getMessage();
                $_SESSION['tipo'] = "danger";
                $_SESSION['icono'] = "ban";
            }
        }
        redireccionar('menus');
    }
}
