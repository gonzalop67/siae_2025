<?php
class Usuarios extends Controlador
{
    private $perfilModelo;
    private $usuarioModelo;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        $this->perfilModelo = $this->modelo('Perfil');
        $this->usuarioModelo = $this->modelo('Usuario');
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

    public function upload_image()
    {
        if (isset($_FILES["foto"])) {
            $extension = explode('.', $_FILES['foto']['name']);
            $new_name = rand() . '.' . $extension[1];
            $destination = dirname(dirname(dirname(__FILE__))) . '/public/uploads/' . $new_name;
            move_uploaded_file($_FILES['foto']['tmp_name'], $destination);
            return $new_name;
        }
    }

    public function insert()
    {
        $us_titulo = trim($_POST['abreviatura']);
        $us_apellidos = preg_replace('/\s+/', ' ', trim($_POST['apellidos']));
        $us_nombres = preg_replace('/\s+/', ' ', trim($_POST['nombres']));

        $apellidos = explode(" ", $us_apellidos);
        $nombres = explode(" ", $us_nombres);

        $us_shortname = trim($_POST['nombre_corto']);

        if ($us_shortname !== "") {
            $us_shortname = preg_replace('/\s+/', ' ', $us_shortname);
        } else {
            $us_shortname = $us_titulo . " " . $nombres[0] . " " . $apellidos[0];
        }

        $us_fullname = $us_apellidos . " " . $us_nombres;

        $us_login = preg_replace('/\s+/', ' ', trim($_POST['usuario']));

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        if ($this->usuarioModelo->existeUsuarioPorNombreCompleto($us_fullname)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Usuario [$us_fullname] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else if ($this->usuarioModelo->existeUsuarioPorNombreUsuario($us_login)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Nombre de Usuario [$us_login] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $image = $this->upload_image();
                // $image = "imagen.png";

                $datos = [
                    'us_titulo' => $us_titulo,
                    'us_apellidos' => $us_apellidos,
                    'us_nombres' => $us_nombres,
                    'us_login' => $_POST['usuario'],
                    'us_password' => Encrypter::encrypt($_POST['password']),
                    'us_shortname' => $us_shortname,
                    'us_fullname' => $us_fullname,
                    'us_genero' => $_POST['genero'],
                    'us_foto' => $image,
                    'us_activo' => $_POST['activo'],
                    'perfiles' => $_POST['perfiles']
                ];

                //
                // print_r("<pre>");
                // print_r($datos);
                // print_r("</pre>");
                // die();
                //

                $this->usuarioModelo->insertarUsuario($datos);

                $ok = true;
                $_SESSION['mensaje'] = "El Usuario fue insertado exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "El Usuario no fue insertado exitosamente. Error: " . $ex->getMessage();
                $tipo_mensaje = "error";
            }
        }

        echo json_encode(array(
            'ok' => $ok,
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'tipo_mensaje' => $tipo_mensaje
        ));
    }
}
