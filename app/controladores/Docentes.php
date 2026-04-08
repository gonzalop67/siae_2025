<?php
class Docentes extends Controlador
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
        $datos = [
            'titulo' => 'Docente Dashboard',
            'dashboard' => 'Docente',
            'nombreVista' => 'docentes/dashboard.php'
        ];
        $this->vista('admin/index', $datos);
    }
}
