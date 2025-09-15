<?php
class Auth extends Controlador
{
    private $perfilModelo;
    private $jornadaModelo;
    private $usuarioModelo;
    private $estudianteModelo;
    private $institucionModelo;

    public function __construct()
    {
        $this->perfilModelo = $this->modelo('Perfil');
        $this->jornadaModelo = $this->modelo('Jornada');
        $this->usuarioModelo = $this->modelo('Usuario');
        $this->estudianteModelo = $this->modelo('Estudiante');
        $this->institucionModelo = $this->modelo('Institucion');
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
        $id_periodo_lectivo = $_POST["periodo"];
        // Verify data login
        $clave = Encrypter::encrypt($password);
        $usuario = $this->usuarioModelo->obtenerUsuario($username, $clave, $id_perfil);

        //
        // print_r("<pre>");
        // print_r($usuario);
        // print_r("</pre>");
        // die();
        //

        if (!empty($usuario)) {
            session_start();
            $_SESSION['usuario_logueado'] = true;
            $_SESSION['id_periodo_lectivo'] = $id_periodo_lectivo;
            $_SESSION['id_usuario'] = $usuario->id_usuario;
            $_SESSION['pe_nombre'] = $usuario->pe_nombre;
            $_SESSION['id_perfil'] = $id_perfil;
            $_SESSION['cambio_paralelo'] = 0;

            echo json_encode(array(
                'error' => false,
                'id_usuario' => $usuario->id_usuario,
                'pe_nombre' => $usuario->pe_nombre
            ));
        } else {
            echo json_encode(array(
                'error' => true,
                'id_usuario' => 0,
                'pe_nombre' => ''
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
