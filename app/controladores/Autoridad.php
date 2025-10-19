<?php
class Autoridad extends Controlador
{

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
    }

    public function index()
    {
        //
    }

    public function dashboard()
    {
        // $numero_autoridades = $this->usuarioModelo->contarAutoridades();
        // $numero_docentes = $this->usuarioModelo->contarDocentes();
        // $id_periodo_lectivo = $_SESSION['id_periodo_lectivo'];
        // $numero_estudiantes = $this->estudianteModelo->contarEstudiantes($id_periodo_lectivo);
        // $num_representantes = $this->usuarioModelo->contarRepresentantes($id_periodo_lectivo);
        // $jornadas = $this->jornadaModelo->obtenerJornadasPorPeriodoLectivo($id_periodo_lectivo);
        $datos = [
            'titulo' => 'Autoridad Dashboard',
            'dashboard' => 'Autoridad',
        //     'numero_autoridades' => $numero_autoridades,
        //     'numero_docentes' => $numero_docentes,
        //     'numero_estudiantes' => $numero_estudiantes,
        //     'num_representantes' => $num_representantes,
        //     'jornadas' => $jornadas,
            'nombreVista' => 'autoridad/dashboard.php'
        ];
        $this->vista('admin/index', $datos);
    }
}
