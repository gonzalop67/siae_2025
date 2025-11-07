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

    public function insert()
    {
        $id_tipo_documento = trim($_POST['id_tipo_documento']);
        $es_cedula = strtoupper(trim($_POST['es_cedula']));
        $es_apellidos = preg_replace('/\s+/', ' ', strtoupper(trim($_POST['es_apellidos'])));
        $es_nombres = strtoupper(trim($_POST['es_nombres']));
        $es_nombre_completo = $es_apellidos . " " . $es_nombres;
        $es_fec_nacim = trim($_POST['es_fec_nacim']);
        $es_direccion = trim($_POST['es_direccion']);
        $es_sector = trim($_POST['es_sector']);
        $es_telefono = trim($_POST['es_telefono']);
        $es_email = trim($_POST['es_email']);
        $id_def_genero = trim($_POST['id_def_genero']);
        $id_def_nacionalidad = trim($_POST['id_def_nacionalidad']);

        $datos = [
            'id_tipo_documento' => $id_tipo_documento,
            'id_def_genero' => $id_def_genero,
            'id_def_nacionalidad' => $id_def_nacionalidad,
            'es_apellidos' => $es_apellidos,
            'es_nombres' => $es_nombres,
            'es_nombre_completo' => $es_nombre_completo,
            'es_cedula' => $es_cedula,
            'es_email' => $es_email,
            'es_sector' => $es_sector,
            'es_direccion' => $es_direccion,
            'es_telefono' => $es_telefono,
            'es_fec_nacim' => $es_fec_nacim
        ];

        // print_r("<pre>");
        // print_r($datos);
        // print_r("</pre>");
        // die();

        // Primero comprobar si ya existen los nombres y apellidos del estudiante
        if ($this->estudianteModelo->existeNombreEstudiante($es_apellidos, $es_nombres)) {
            $data = array(
                "titulo"       => "Ocurrió un error inesperado.",
                "mensaje"      => "Ya existe el estudiante en la base de datos...",
                "tipo_mensaje" => "error"
            );
        } else {
            $data = array(
                "titulo"       => "Operación exitosa.",
                "mensaje"      => "El estudiante fue insertado exitosamente.",
                "tipo_mensaje" => "success"
            );
        }

        echo json_encode($data);
    }
}
