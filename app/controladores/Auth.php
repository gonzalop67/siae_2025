<?php
class Auth extends Controlador
{
    private $perfilModelo;
    private $jornadaModelo;
    private $usuarioModelo;
    private $estudianteModelo;
    private $institucionModelo;
    private $periodoLectivoModelo;

    public function __construct()
    {
        $this->perfilModelo = $this->modelo('Perfil');
        $this->jornadaModelo = $this->modelo('Jornada');
        $this->usuarioModelo = $this->modelo('Usuario');
        $this->estudianteModelo = $this->modelo('Estudiante');
        $this->institucionModelo = $this->modelo('Institucion');
        $this->periodoLectivoModelo = $this->modelo('PeriodoLectivo');
    }

    public function index()
    {
        $perfiles = $this->perfilModelo->obtenerPerfiles();
        $nombreInstitucion = $this->institucionModelo->obtenerNombreInstitucion();
        $datos = [
            'perfiles' => $perfiles,
            'nombreInstitucion' => $nombreInstitucion
        ];
        $this->vista('auth/login', $datos);
    }

    public function login()
    {
        // Collect data POST
        $username = $_POST["usuario"];
        $password = $_POST["clave"];
        $id_perfil = $_POST["perfil"];
        // Verify data login
        $clave = Encrypter::encrypt($password);
        $usuario = $this->usuarioModelo->obtenerUsuario($username, $clave, $id_perfil);
        if ($usuario->pe_nombre !== "ADMINISTRADOR" && $usuario->pe_nombre !== "TUTOR") {
            $id_periodo_lectivo = $_POST["periodo"];
            $periodoActual = $this->periodoLectivoModelo->obtenerPeriodoLectivo($id_periodo_lectivo);
            $nombrePeriodo = $periodoActual->pe_anio_inicio . " - " . $$periodoActual->pe_anio_fin;
        }

        if (!empty($usuario)) {
            session_start();
            $_SESSION['usuario_logueado'] = true;
            if ($usuario->pe_nombre !== "ADMINISTRADOR" && $usuario->pe_nombre !== "TUTOR") {
                $_SESSION['id_periodo_lectivo'] = $id_periodo_lectivo;
                $_SESSION['nombrePeriodo'] = $nombrePeriodo;
            }
            $_SESSION['id_usuario'] = $usuario->id_usuario;
            $_SESSION['id_perfil'] = $id_perfil;
            $_SESSION['nombrePerfil'] = $usuario->pe_nombre;
            $_SESSION['cambio_paralelo'] = 0;

            echo json_encode(array(
                'error' => false,
                'id_usuario' => $usuario->id_usuario,
                'nombrePerfil' => $usuario->pe_nombre
            ));
        } else {
            echo json_encode(array(
                'error' => true,
                'id_usuario' => 0,
                'nombrePerfil' => ''
            ));
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();
        redireccionar('Auth');
    }
}
