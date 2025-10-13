<?php
class Areas extends Controlador
{
    private $areaModelo;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        $this->areaModelo = $this->modelo('Area');
    }

    public function index()
    {
        $datos = [
            'titulo' => 'Areas',
            'dashboard' => 'Admin',
            'nombreVista' => 'admin/areas/index.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function create()
    {
        $datos = [
            'titulo' => 'Crear Nueva Área',
            'nombreVista' => 'admin/areas/create.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function store()
    {
        $ar_nombre = strtoupper(preg_replace('/\s+/', ' ', trim($_POST['nombre'])));
        $ar_activo = $_POST['activo'];

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $datos = [ 
            'ar_nombre' => $ar_nombre,
            'ar_activo' => $ar_activo
        ];

        if ($this->areaModelo->existeNombre($ar_nombre)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Área [$ar_nombre] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->areaModelo->insertar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "El Área fue insertada exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "El Área no fue insertada exitosamente. Error: " . $ex->getMessage();
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
        $areaActual = $this->areaModelo->obtener($id);

        $datos = [
            'titulo' => 'Editar Área',
            'dashboard' => 'Admin',
            'area' => $areaActual,
            'nombreVista' => 'admin/areas/edit.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function update()
    {
        $id_area = $_POST['id_area'];
        $areaActual = $this->areaModelo->obtener($id_area);

        $ar_nombre = strtoupper(preg_replace('/\s+/', ' ', trim($_POST['nombre'])));
        $ar_activo = $_POST['activo'];

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $datos = [
            'id_area' => $id_area, 
            'ar_nombre' => $ar_nombre,
            'ar_activo' => $ar_activo
        ];

        if ($areaActual->ar_nombre != $ar_nombre && $this->areaModelo->existeNombre($ar_nombre)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Área [$ar_nombre] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->areaModelo->actualizar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "El Área fue actualizada exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "El Área no fue actualizada exitosamente. Error: " . $ex->getMessage();
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
            $this->areaModelo->eliminar($id);
            // Mensaje de éxito
            $_SESSION['mensaje'] = "Área eliminada exitosamente de la base de datos.";
            $_SESSION['tipo'] = "success";
            $_SESSION['icono'] = "check";
        } catch (PDOException $e) {
            $_SESSION['mensaje'] = "El Área no fue eliminada exitosamente. Error: " . $e->getMessage();
            $_SESSION['tipo'] = "danger";
            $_SESSION['icono'] = "ban";
        }
        redireccionar('areas');
    }
}