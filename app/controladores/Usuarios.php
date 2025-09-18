<?php
class Usuarios extends Controlador
{
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
        $datos = [
            'titulo' => 'Usuarios',
            'dashboard' => 'Admin',
            'nombreVista' => 'admin/usuarios/index.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function create()
    {
        $perfiles = $this->perfilModelo->obtenerPerfiles();
        $datos = [
            'titulo' => 'Crear Usuario',
            'dashboard' => 'Admin',
            'perfiles' => $perfiles,
            'nombreVista' => 'admin/usuarios/create.php'
        ];
        $this->vista('admin/index', $datos);
    }
}
