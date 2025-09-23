<?php 
class Perfiles extends Controlador {
    private $perfilModelo;
    
    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        $this->perfilModelo = $this->modelo('Perfil');
    }

    public function index()
    {
        $perfiles = $this->perfilModelo->obtenerPerfiles();
        $datos = [
            'titulo' => 'Perfiles CRUD',
            'dashboard' => 'Admin',
            'perfiles' => $perfiles, 
            'nombreVista' => 'admin/perfiles/index.php'
        ];
        $this->vista('admin/index', $datos);
    }
}
?>