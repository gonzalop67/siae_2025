<?php
class Cursos extends Controlador
{
    private $cursoModelo;
    private $subnivelModelo;
    private $especialidadModelo;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        $this->cursoModelo = $this->modelo('Curso');
        $this->subnivelModelo = $this->modelo('Subnivel_educacion');
        $this->especialidadModelo = $this->modelo('Especialidad');
    }

    public function index()
    {
        $datos = [
            'titulo' => 'Cursos',
            'dashboard' => 'AdminUE',
            'nombreVista' => 'admin-ue/cursos/index.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function create()
    {
        $institucion_id = $_SESSION['institucion_id'];
        $subniveles = $this->subnivelModelo->obtenerSubniveles($institucion_id);
        $datos = [
            'titulo'         => 'Crear Nuevo Curso',
            'dashboard'      => 'AdminUE',
            'subniveles'     => $subniveles,
            'nombreVista'    => 'admin-ue/cursos/create.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function store()
    {
        $nombre = preg_replace('/\s+/', ' ', trim($_POST['nombre']));
        $nombre_corto = preg_replace('/\s+/', ' ', trim($_POST['nombre_corto']));
        $abreviatura = preg_replace('/\s+/', ' ', trim($_POST['abreviatura']));
        $subniveles = $_POST['subniveles'];
        
        $datos = [
            'cu_nombre' => $nombre,
            'cu_shortname' => $nombre_corto,
            'cu_abreviatura' => $abreviatura,
            'subniveles' => $subniveles
        ];

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        if ($this->cursoModelo->existeCampo('cu_nombre', $nombre)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Curso [$nombre] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else if ($this->cursoModelo->existeCampo('cu_shortname', $nombre_corto)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Nombre Corto [$nombre_corto] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else if ($this->cursoModelo->existeCampo('cu_abreviatura', $abreviatura)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe la Abreviatura [$abreviatura] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->cursoModelo->insertar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "El Curso fue insertado exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "El Curso no fue insertado exitosamente. Error: " . $ex->getMessage();
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

    public function edit($id) {
        $institucion_id = $_SESSION['institucion_id'];
        $subniveles = $this->subnivelModelo->obtenerSubniveles($institucion_id);
        $subnivelesCurso = $this->cursoModelo->obtenerSubnivelesCurso($id);
        $curso = $this->cursoModelo->obtener($id);
        $datos = [
            'titulo'          => 'Crear Nuevo Curso',
            'dashboard'       => 'AdminUE',
            'subniveles'      => $subniveles,
            'subnivelesCurso' => $subnivelesCurso,
            'curso'           => $curso,
            'nombreVista'     => 'admin-ue/cursos/edit.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function update()
    {
        $id_curso = $_POST['id_curso'];
        $nombre = preg_replace('/\s+/', ' ', trim($_POST['nombre']));
        $nombre_corto = preg_replace('/\s+/', ' ', trim($_POST['nombre_corto']));
        $abreviatura = preg_replace('/\s+/', ' ', trim($_POST['abreviatura']));
        $subniveles = $_POST['subniveles'];
        
        $datos = [
            'id_curso' => $id_curso,
            'cu_nombre' => $nombre,
            'cu_shortname' => $nombre_corto,
            'cu_abreviatura' => $abreviatura,
            'subniveles' => $subniveles
        ];

        // print_r("<pre>");
        // print_r($datos);
        // print_r("</pre>");
        // die();

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $cursoActual = $this->cursoModelo->obtener($id_curso);

        if ($cursoActual->cu_nombre != $nombre && $this->cursoModelo->existeCampo('cu_nombre', $nombre)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Curso [$nombre] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else if ($cursoActual->cu_shortname != $nombre_corto && $this->cursoModelo->existeCampo('cu_shortname', $nombre_corto)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Nombre Corto [$nombre_corto] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else if ($cursoActual->cu_abreviatura != $abreviatura && $this->cursoModelo->existeCampo('cu_abreviatura', $abreviatura)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe la Abreviatura [$abreviatura] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->cursoModelo->actualizar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "El Curso fue insertado exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "El Curso no fue insertado exitosamente. Error: " . $ex->getMessage();
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