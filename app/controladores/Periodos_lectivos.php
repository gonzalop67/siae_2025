<?php
class Periodos_lectivos extends Controlador
{
    private $periodoLectivoModelo;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        $this->periodoLectivoModelo = $this->modelo('PeriodoLectivo');
    }

    public function index()
    {
        $institucion_id = $_SESSION['institucion_id'];
        $periodos_lectivos = $this->periodoLectivoModelo->obtenerPorInstitucion($institucion_id);
        $datos = [
            'titulo' => 'Perfiles CRUD',
            'dashboard' => 'AdminUE',
            'periodos_lectivos' => $periodos_lectivos,
            'nombreVista' => 'admin-ue/periodos_lectivos/index.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function create()
    {
        $datos = [
            'titulo' => 'Crear Periodo Lectivo',
            'dashboard' => 'AdminUE',
            'nombreVista' => 'admin-ue/periodos_lectivos/create.php'
        ];
        $this->vista('admin/index', $datos);
    }
}