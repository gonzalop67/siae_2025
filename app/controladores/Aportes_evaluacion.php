<?php
class Aportes_evaluacion extends Controlador
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
        $datos = [
            'titulo' => 'Aportes de Evaluación',
            'dashboard' => 'Admin',
            'nombreVista' => 'admin/aportes_evaluacion/index.php'
        ];
        $this->vista('admin/index', $datos);
    }
}