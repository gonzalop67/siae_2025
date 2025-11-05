<?php
class Paralelos extends Controlador
{
    private $jornadaModelo;
    private $paraleloModelo;
    private $cursoSubnivelModelo;
    private $periodoLectivoModelo;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        $this->jornadaModelo = $this->modelo('Jornada');
        $this->paraleloModelo = $this->modelo('Paralelo');
        $this->cursoSubnivelModelo = $this->modelo('CursoSubnivel');
        $this->periodoLectivoModelo = $this->modelo('PeriodoLectivo');
    }

    public function index()
    {
        $paralelos = $this->paraleloModelo->obtenerParalelosVigentes();
        $datos = [
            'titulo' => 'CRUD Paralelos',
            'dashboard' => 'AdminUE',
            'paralelos' => $paralelos,
            'nombreVista' => 'admin-ue/paralelos/index.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function create()
    {
        $jornadas = $this->jornadaModelo->obtenerJornadas();
        $cursos = $this->cursoSubnivelModelo->obtenerCursosSubnivel();
        $periodos_lectivos = $this->periodoLectivoModelo->obtenerPeriodosLectivosActuales();
        $datos = [
            'titulo' => 'Crear Paralelos',
            'dashboard' => 'AdminUE',
            'cursos' => $cursos,
            'jornadas' => $jornadas,
            'periodos_lectivos' => $periodos_lectivos,
            'nombreVista' => 'admin-ue/paralelos/create.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function insert()
    {
        $pa_nombre = strtoupper(trim($_POST['nombre']));
        $jornada_id = $_POST['jornada'];
        $curso_subnivel_id = $_POST['curso'];
        $periodo_lectivo_id = $_POST['periodo_lectivo'];

        $datos = [
            'pa_nombre' => $pa_nombre,
            'jornada_id' => $jornada_id,
            'curso_subnivel_id' => $curso_subnivel_id,
            'periodo_lectivo_id' => $periodo_lectivo_id
        ];

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        if ($this->paraleloModelo->existeNombre($pa_nombre, $curso_subnivel_id, $jornada_id, $periodo_lectivo_id)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Paralelo [$pa_nombre] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->paraleloModelo->insertar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "El Paralelo fue insertado exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "El Paralelo no fue insertado exitosamente. Error: " . $ex->getMessage();
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
        $paralelo = $this->paraleloModelo->obtener($id);
        $jornadas = $this->jornadaModelo->obtenerJornadas();
        $cursos = $this->cursoSubnivelModelo->obtenerCursosSubnivel();
        $periodos_lectivos = $this->periodoLectivoModelo->obtenerPeriodosLectivosActuales();
        $datos = [
            'titulo' => 'Crear Paralelos',
            'dashboard' => 'AdminUE',
            'cursos' => $cursos,
            'jornadas' => $jornadas,
            'paralelo' => $paralelo,
            'periodos_lectivos' => $periodos_lectivos,
            'nombreVista' => 'admin-ue/paralelos/edit.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function update()
    {
        $id_paralelo = $_POST['id_paralelo'];
        $pa_nombre = strtoupper(trim($_POST['nombre']));
        $jornada_id = $_POST['jornada'];
        $curso_subnivel_id = $_POST['curso'];
        $periodo_lectivo_id = $_POST['periodo_lectivo'];

        $paraleloActual = $this->paraleloModelo->obtener($id_paralelo);

        $datos = [
            'id_paralelo'        => $id_paralelo,
            'pa_nombre'          => $pa_nombre,
            'jornada_id'         => $jornada_id,
            'curso_subnivel_id'  => $curso_subnivel_id,
            'periodo_lectivo_id' => $periodo_lectivo_id
        ];

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        if ($paraleloActual->pa_nombre != $pa_nombre && $this->paraleloModelo->existeNombre($pa_nombre, $curso_subnivel_id, $jornada_id, $periodo_lectivo_id)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Paralelo [$pa_nombre] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->paraleloModelo->actualizar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "El Paralelo fue actualizado exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "El Paralelo no fue actualizado exitosamente. Error: " . $ex->getMessage();
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
}