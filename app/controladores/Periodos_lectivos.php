<?php
class Periodos_lectivos extends Controlador
{
    private $institucion_id;
    private $modalidadModelo;
    private $subPeriodoModelo;
    private $periodoLectivoModelo;
    private $subnivelEducacionModelo;
    private $quienInsertaComportamiento;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        $this->institucion_id = $_SESSION['institucion_id'];
        $this->modalidadModelo = $this->modelo('Modalidad');
        $this->periodoLectivoModelo = $this->modelo('PeriodoLectivo');
        $this->subPeriodoModelo = $this->modelo('Subperiodo_evaluacion');
        $this->subnivelEducacionModelo = $this->modelo('Subnivel_educacion');
        $this->quienInsertaComportamiento = $this->modelo('Quien_inserta_comportamiento');
    }

    public function index()
    {
        $periodos_lectivos = $this->periodoLectivoModelo->obtenerPorInstitucion($this->institucion_id);
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
        $modalidades = $this->modalidadModelo->obtenerModalidades();
        $sub_periodos_evaluacion = $this->subPeriodoModelo->obtenerTodos();
        $quien_inserta_comportamiento = $this->quienInsertaComportamiento->obtenerTodos();
        $niveles_educacion = $this->subnivelEducacionModelo->obtenerSubniveles($this->institucion_id);
        $datos = [
            'titulo' => 'Crear Periodo Lectivo',
            'dashboard' => 'AdminUE',
            'modalidades' => $modalidades,
            'niveles_educacion' => $niveles_educacion,
            'sub_periodos_evaluacion' => $sub_periodos_evaluacion,
            'quien_inserta_comportamiento' => $quien_inserta_comportamiento,
            'nombreVista' => 'admin-ue/periodos_lectivos/create.php'
        ];
        $this->vista('admin/index', $datos);
    }
}