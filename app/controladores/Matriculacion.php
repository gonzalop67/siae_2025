<?php
class Matriculacion extends Controlador
{
    private $paraleloModelo;
    private $defGeneroModelo;
    private $estudianteModelo;
    private $tipoDocumentoModelo;
    private $defNacionalidadModelo;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        $this->paraleloModelo = $this->modelo('Paralelo');
        $this->defGeneroModelo = $this->modelo('Def_genero');
        $this->estudianteModelo = $this->modelo('Estudiante');
        $this->tipoDocumentoModelo = $this->modelo('Tipo_documento');
        $this->defNacionalidadModelo = $this->modelo('Def_nacionalidad');
    }

    public function index()
    {
        $id_periodo_lectivo = $_SESSION['id_periodo_lectivo'];
        $def_generos = $this->defGeneroModelo->obtenerDefGeneros();
        $tipos_documento = $this->tipoDocumentoModelo->obtenerTiposDocumento();
        $paralelos = $this->paraleloModelo->obtenerParalelos($id_periodo_lectivo);
        $def_nacionalidades = $this->defNacionalidadModelo->obtenerDefNacionalidades();
        $datos = [
            'titulo' => 'Matriculación de Paralelos',
            'paralelos' => $paralelos,
            'tipos_documento' => $tipos_documento,
            'def_generos' => $def_generos,
            'def_nacionalidades' => $def_nacionalidades,
            'dashboard' => 'Secretaria',
            'nombreVista' => 'secretaria/matriculacion/index.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function listar()
    {
        $id_paralelo = $_POST['id_paralelo'];
        echo $this->estudianteModelo->listarEstudiantesParalelo($id_paralelo);
    }

    public function create()
    {
        $datos = [
            'titulo' => 'Insertar Estudiante',
            'dashboard' => 'Secretaria',
            'nombreVista' => 'secretaria/matriculacion/create.php'
        ];
        $this->vista('admin/index', $datos);
    }
}
