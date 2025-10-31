<?php
class Categorias extends Controlador
{
    private $categoriaModelo;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        $this->categoriaModelo = $this->modelo('Categoria');
    }

    public function index()
    {
        $categorias = $this->categoriaModelo->obtenerTodos();
        $datos = [
            'titulo' => 'Categorías',
            'dashboard' => 'AdminUE',
            'categorias' => $categorias,
            'nombreVista' => 'admin-ue/categorias/index.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function create()
    {
        $datos = [
            'titulo' => 'Crear Categoría',
            'dashboard' => 'AdminUE',
            'nombreVista' => 'admin-ue/categorias/create.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function store()
    {
        $nombre = preg_replace('/\s+/', ' ', trim($_POST['nombre']));

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $datos = [ 
            'nombre' => $nombre
        ];

        if ($this->categoriaModelo->existeNombre($nombre)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe la categoría [$nombre] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->categoriaModelo->insertar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "La Categoría de Especialidad fue insertada exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "La Categoría de Especialidad no fue insertada exitosamente. Error: " . $ex->getMessage();
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
        $categoria = $this->categoriaModelo->obtener($id);

        $datos = [
            'titulo' => 'Crear Categoría',
            'dashboard' => 'AdminUE',
            'categoria' => $categoria,
            'nombreVista' => 'admin-ue/categorias/edit.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function update()
    {
        $id_categoria = $_POST['id_categoria'];
        $nombre = preg_replace('/\s+/', ' ', trim($_POST['nombre']));

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $datos = [ 
            'id_categoria' => $id_categoria,
            'nombre' => $nombre
        ];

        $categoriaActual = $this->categoriaModelo->obtener($id_categoria);

        if ($categoriaActual->nombre != $nombre && $this->categoriaModelo->existeNombre($nombre)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe la categoría [$nombre] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->categoriaModelo->actualizar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "La Categoría de Especialidad fue actualizada exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "La Categoría de Especialidad no fue actualizada exitosamente. Error: " . $ex->getMessage();
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
            $this->categoriaModelo->eliminar($id);
            // Mensaje de éxito
            $_SESSION['mensaje'] = "Categoría de Especialidad eliminada exitosamente de la base de datos.";
            $_SESSION['tipo'] = "success";
            $_SESSION['icono'] = "check";
        } catch (PDOException $e) {
            $_SESSION['mensaje'] = "La Categoría de Especialidad no fue eliminada exitosamente. Error: " . $e->getMessage();
            $_SESSION['tipo'] = "danger";
            $_SESSION['icono'] = "ban";
        }
        redireccionar('categorias');
    }
}
