<?php
class Admin extends Controlador
{

    public function __construct()
    {
    }

    public function dashboard()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        // $numero_autoridades = $this->usuarioModelo->contarAutoridades();
        // $numero_docentes = $this->usuarioModelo->contarDocentes();
        // $id_periodo_lectivo = $_SESSION['id_periodo_lectivo'];
        // $numero_estudiantes = $this->estudianteModelo->contarEstudiantes($id_periodo_lectivo);
        // $num_representantes = $this->usuarioModelo->contarRepresentantes($id_periodo_lectivo);
        // $jornadas = $this->jornadaModelo->obtenerJornadasPorPeriodoLectivo($id_periodo_lectivo);
        $datos = [
            'titulo' => 'Admin Dashboard',
        //     'numero_autoridades' => $numero_autoridades,
        //     'numero_docentes' => $numero_docentes,
        //     'numero_estudiantes' => $numero_estudiantes,
        //     'num_representantes' => $num_representantes,
        //     'jornadas' => $jornadas,
            'nombreVista' => 'admin/dashboard.php'
        ];
        $this->vista('admin/index', $datos);
    }
}
