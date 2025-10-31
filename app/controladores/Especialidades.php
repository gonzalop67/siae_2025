<?php
class Especialidades extends Controlador
{
    private $categoriaModelo;
    private $especialidadModelo;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        $this->categoriaModelo = $this->modelo('Categoria');
        $this->especialidadModelo = $this->modelo('Especialidad');
    }

    public function index()
    {
        $especialidades = $this->especialidadModelo->obtenerEspecialidades();
        $datos = [
            'titulo' => 'Especialidades',
            'dashboard' => 'AdminUE',
            'especialidades' => $especialidades,
            'nombreVista' => 'admin-ue/especialidades/index.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function create()
    {
        $categorias = $this->categoriaModelo->obtenerTodos();
        $datos = [
            'titulo' => 'Crear Especialidad',
            'dashboard' => 'AdminUE',
            'categorias' => $categorias,
            'nombreVista' => 'admin-ue/especialidades/create.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function store()
    {
        $id_categoria = $_POST['categoria'];
        $es_figura = preg_replace('/\s+/', ' ', strtoupper(trim($_POST['figura'])));
        $es_abreviatura = strtoupper(trim($_POST['abreviatura']));

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $datos = [
            'id_categoria' => $id_categoria,
            'es_figura' => $es_figura,
            'es_abreviatura' => $es_abreviatura
        ];

        if ($this->especialidadModelo->existeCampo('es_figura', $es_figura)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe la Figura Profesional [$es_figura] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else if ($this->especialidadModelo->existeCampo('es_abreviatura', $es_abreviatura)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe la Abreviatura [$es_abreviatura] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->especialidadModelo->insertar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "La Especialidad fue insertada exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "La Especialidad no fue insertada exitosamente. Error: " . $ex->getMessage();
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
        $categorias = $this->categoriaModelo->obtenerTodos();
        $especialidad = $this->especialidadModelo->obtener($id);
        $datos = [
            'titulo' => 'Crear Especialidad',
            'dashboard' => 'AdminUE',
            'categorias' => $categorias,
            'especialidad' => $especialidad,
            'nombreVista' => 'admin-ue/especialidades/edit.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function update()
    {
        $id_especialidad = $_POST['id_especialidad'];
        $id_categoria = $_POST['categoria'];
        $es_figura = preg_replace('/\s+/', ' ', strtoupper(trim($_POST['figura'])));
        $es_abreviatura = strtoupper(trim($_POST['abreviatura']));

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        $datos = [
            'id_especialidad' => $id_especialidad,
            'id_categoria' => $id_categoria,
            'es_figura' => $es_figura,
            'es_abreviatura' => $es_abreviatura
        ];

        $especialidadActual = $this->especialidadModelo->obtener($id_especialidad);

        if ($especialidadActual->es_figura != $es_figura && $this->especialidadModelo->existeCampo('es_figura', $es_figura)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe la Figura Profesional [$es_figura] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else if ($especialidadActual->es_abreviatura != $es_abreviatura && $this->especialidadModelo->existeCampo('es_abreviatura', $es_abreviatura)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe la Abreviatura [$es_abreviatura] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $this->especialidadModelo->actualizar($datos);
                $ok = true;
                $_SESSION['mensaje'] = "La Especialidad fue actualizada exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "La Especialidad no fue actualizada exitosamente. Error: " . $ex->getMessage();
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
            $this->especialidadModelo->eliminar($id);
            // Mensaje de éxito
            $_SESSION['mensaje'] = "Especialidad eliminada exitosamente de la base de datos.";
            $_SESSION['tipo'] = "success";
            $_SESSION['icono'] = "check";
        } catch (PDOException $e) {
            $_SESSION['mensaje'] = "La Especialidad no fue eliminada exitosamente. Error: " . $e->getMessage();
            $_SESSION['tipo'] = "danger";
            $_SESSION['icono'] = "ban";
        }
        redireccionar('categorias');
    }
}
