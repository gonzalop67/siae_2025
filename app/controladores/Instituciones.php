<?php 
class Instituciones extends Controlador {
    private $institucionModelo;
    
    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        $this->institucionModelo = $this->modelo('Institucion');
    }

    public function index()
    {
        $instituciones = $this->institucionModelo->obtenerInstituciones();
        $datos = [
            'titulo' => 'Institución CRUD',
            'dashboard' => 'Admin',
            'instituciones' => $instituciones, 
            'nombreVista' => 'admin/instituciones/index.php'
        ];
        $this->vista('admin/index', $datos);
    }
}
?>