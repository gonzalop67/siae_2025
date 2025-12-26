<?php
class Aportes_evaluacion extends Controlador
{
    private $tipoAporteModelo;
    private $subPeriodoEvaluacionModelo;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        $this->tipoAporteModelo = $this->modelo('Tipo_aporte');
        $this->subPeriodoEvaluacionModelo = $this->modelo('Subperiodo_evaluacion');
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

    public function create()
    {
        $tipos_aporte = $this->tipoAporteModelo->obtenerTodos();
        $sub_periodos = $this->subPeriodoEvaluacionModelo->obtenerTodos();
        $datos = [
            'titulo' => 'Crear Aporte de Evaluación',
            'dashboard' => 'Admin',
            'tipos_aporte' => $tipos_aporte,
            'sub_periodos' => $sub_periodos,
            'nombreVista' => 'admin/aportes_evaluacion/create.php'
        ];
        $this->vista('admin/index', $datos);
    }
}