<?php
class Jornadas extends Controlador
{
    private $jornadaModelo;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        $this->jornadaModelo = $this->modelo('Jornada');
    }

    public function index()
    {
        $jornadas = $this->jornadaModelo->obtenerJornadas();
        $datos = [
            'titulo' => 'CRUD Jornadas',
            'dashboard' => 'AdminUE',
            'jornadas' => $jornadas,
            'nombreVista' => 'admin-ue/jornadas/index.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function create()
    {
        $datos = [
            'titulo'         => 'Crear Nueva Jornada',
            'dashboard'      => 'AdminUE',
            'nombreVista'    => 'admin-ue/jornadas/create.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function insert()
    {
        $jo_nombre = strtoupper(trim($_POST['nombre']));

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $datos = [ 
            'jo_nombre' => $jo_nombre
        ];

        if ($this->jornadaModelo->existeNombre($jo_nombre)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe la Jornada [$jo_nombre] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->jornadaModelo->insertar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "La Jornada fue insertada exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "La Jornada no fue insertada exitosamente. Error: " . $ex->getMessage();
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
        $jornada = $this->jornadaModelo->obtener($id);
        $datos = [
            'titulo'         => 'Editar Jornada',
            'dashboard'      => 'AdminUE',
            'jornada'        => $jornada,
            'nombreVista'    => 'admin-ue/jornadas/edit.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function update()
    {
        $id_jornada = $_POST['id_jornada'];
        $jo_nombre = strtoupper(trim($_POST['nombre']));

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $datos = [ 
            'id_jornada' => $id_jornada,
            'jo_nombre'  => $jo_nombre
        ];

        $jornadaActual = $this->jornadaModelo->obtener($id_jornada);

        if ($jornadaActual->jo_nombre != $jo_nombre && $this->jornadaModelo->existeNombre($jo_nombre)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe la Jornada [$jo_nombre] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->jornadaModelo->actualizar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "La Jornada fue actualizada exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "La Jornada no fue actualizada exitosamente. Error: " . $ex->getMessage();
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
            $this->jornadaModelo->eliminar($id);
            // Mensaje de éxito
            $_SESSION['mensaje'] = "Jornada eliminada exitosamente de la base de datos.";
            $_SESSION['tipo'] = "success";
            $_SESSION['icono'] = "check";
        } catch (PDOException $e) {
            $_SESSION['mensaje'] = "La Jornada no fue eliminada exitosamente. Error: " . $e->getMessage();
            $_SESSION['tipo'] = "danger";
            $_SESSION['icono'] = "ban";
        }
        redireccionar('jornadas');
    }

    public function saveNewPositions()
    {
        foreach($_POST['positions'] as $position) {
            $index = $position[0];
            $newPosition = $position[1];

            $this->jornadaModelo->actualizarOrden($index, $newPosition);
        }
    }
}