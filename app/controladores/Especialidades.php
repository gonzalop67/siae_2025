<?php
class Especialidades extends Controlador
{
    private $especialidadModelo;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        $this->especialidadModelo = $this->modelo('Especialidad');
    }

    public function index()
    {
        $datos = [
            'titulo' => 'Especialidades',
            'dashboard' => 'AdminUE',
            'nombreVista' => 'admin-ue/especialidades/index.php'
        ];
        $this->vista('admin/index', $datos);
    }
}
