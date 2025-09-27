<?php
class Modalidades extends Controlador
{
    private $modalidadModelo;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        $this->modalidadModelo = $this->modelo('Modalidad');
    }

    public function index()
    {
        $modalidades = $this->modalidadModelo->obtenerModalidades();
        $datos = [
            'titulo' => 'Modalidades CRUD',
            'dashboard' => 'Admin',
            'modalidades' => $modalidades,
            'nombreVista' => 'admin/modalidades/index.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function create()
    {
        $datos = [
            'titulo' => 'Crear Modalidades',
            'dashboard' => 'Admin',
            'nombreVista' => 'admin/modalidades/create.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function insert()
    {
        $mo_nombre = strtoupper(preg_replace('/\s+/', ' ', trim($_POST['nombre'])));
        $mo_activo = $_POST['activo'];

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $datos = [ 
            'mo_nombre' => $mo_nombre,
            'mo_activo' => $mo_activo
        ];

        if ($this->modalidadModelo->existeNombre($mo_nombre)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe la Modalidad [$mo_nombre] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->modalidadModelo->insertar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "La Modalidad fue insertada exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "La Modalidad no fue insertada exitosamente. Error: " . $ex->getMessage();
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
        $modalidadActual = $this->modalidadModelo->obtenerModalidad($id);

        $datos = [
            'titulo' => 'Editar Modalidad',
            'dashboard' => 'Admin',
            'modalidad' => $modalidadActual,
            'nombreVista' => 'admin/modalidades/edit.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function update()
    {
        $id_modalidad = $_POST['id_modalidad'];
        $modalidadActual = $this->modalidadModelo->obtenerModalidad($id_modalidad);

        $mo_nombre = preg_replace('/\s+/', ' ', trim($_POST['nombre']));
        $mo_activo = $_POST['activo'];

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $datos = [
            'id_modalidad' => $id_modalidad, 
            'mo_nombre' => $mo_nombre,
            'mo_activo' => $mo_activo
        ];

        if ($modalidadActual->mo_nombre != $mo_nombre && $this->modalidadModelo->existeNombreModalidad($mo_nombre)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe La Modalidad [$mo_nombre] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->modalidadModelo->actualizar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "La Modalidad fue actualizada exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "La Modalidad no fue actualizada exitosamente. Error: " . $ex->getMessage();
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
            $this->modalidadModelo->eliminar($id);
            // Mensaje de éxito
            $_SESSION['mensaje'] = "Modalidad eliminada exitosamente de la base de datos.";
            $_SESSION['tipo'] = "success";
            $_SESSION['icono'] = "check";
        } catch (PDOException $e) {
            $_SESSION['mensaje'] = "La Modalidad no fue eliminada exitosamente. Error: " . $e->getMessage();
            $_SESSION['tipo'] = "danger";
            $_SESSION['icono'] = "ban";
        }
        redireccionar('modalidades');
    }
}
