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
        $us_titulo_descripcion = preg_replace('/\s+/', ' ', trim($_POST['descripcion']));
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

        if ($this->usuarioModelo->existeCampo('us_fullname', $us_fullname)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Usuario [$us_fullname] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else if ($this->usuarioModelo->existeCampo('us_login', $us_login)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Nombre de Usuario [$us_login] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {
            try {
                $image = $this->upload_image();

                $institucion_id = $_SESSION['institucion_id'];

                $datos = [
                    'institucion_id' => $institucion_id,
                    'us_titulo' => $us_titulo,
                    'us_titulo_descripcion' => $us_titulo_descripcion,
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

    public function edit($id)
    {
        $usuarioActual = $this->usuarioModelo->obtenerUsuarioPorId($id);

        if (empty($usuarioActual)) {
            echo "No existe el usuario...";
        } else {
            $perfiles = $this->perfilModelo->obtenerPerfiles();
            $perfilesUsuario = $this->usuarioModelo->obtenerPerfilesUsuario($id);

            $datos = [
                'titulo' => 'Editar Usuario',
                'dashboard' => 'Admin',
                'perfiles' => $perfiles,
                'perfilesUsuario' => $perfilesUsuario,
                'usuario' => $usuarioActual,
                'nombreVista' => 'admin/usuarios/edit.php'
            ];

            $this->vista('admin/index', $datos);
        }
    }

    public function update()
    {
        $id_usuario = $_POST['id_usuario'];
        $us_titulo = trim($_POST['abreviatura']);
        $us_titulo_descripcion = preg_replace('/\s+/', ' ', trim($_POST['descripcion']));
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

        $usuarioActual = $this->usuarioModelo->obtenerUsuarioPorId($id_usuario);
        $us_foto = $usuarioActual->us_foto;

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        if ($usuarioActual->us_fullname != $us_fullname && $this->usuarioModelo->existeCampo('us_fullname', $us_fullname)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Usuario [$us_fullname] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else if ($usuarioActual->us_login != $us_login && $this->usuarioModelo->existeCampo('us_login', $us_login)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Nombre de Usuario [$us_login] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {

            if ($_FILES['foto']['name'] != "") {
                // Elimino el archivo de imagen anterior si existe
                $ruta = dirname(dirname(dirname(__FILE__))) . "/public/uploads/";
                $imagenActual = $ruta . $us_foto;

                if (file_exists($imagenActual)) {
                    unlink($imagenActual); // El directorio que contiene los archivos de imagen debe tener permisos de escritura
                }

                $image = $this->upload_image();
            } else {
                $image = $usuarioActual->us_foto;
            }

            $datos = [
                'id_usuario' => $id_usuario,
                'us_titulo' => $us_titulo,
                'us_titulo_descripcion' => $us_titulo_descripcion,
                'us_apellidos' => $us_apellidos,
                'us_nombres' => $us_nombres,
                'us_login' => $us_login,
                'us_password' => Encrypter::encrypt($_POST['password']),
                'us_shortname' => $us_shortname,
                'us_fullname' => $us_fullname,
                'us_genero' => $_POST['genero'],
                'us_foto' => $image,
                'us_activo' => $_POST['activo'],
                'perfiles' => $_POST['perfiles']
            ];

            try {
                $this->usuarioModelo->actualizarUsuario($datos);
                $ok = true;
                $_SESSION['mensaje'] = "El Usuario fue actualizado exitosamente.";
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

    public function delete($id)
    {
        try {
            // Recuperar el nombre del archivo de imagen
            $usuarioActual = $this->usuarioModelo->obtenerUsuarioPorId($id);
            $us_foto = $usuarioActual->us_foto;
            // Eliminar el registro de la base de datos
            $this->usuarioModelo->eliminarUsuario($id);
            // Elimino el archivo de imagen si existe
            $ruta = dirname(dirname(dirname(__FILE__))) . "/public/uploads/";
            $imagenActual = $ruta . $us_foto;

            if (file_exists($imagenActual)) {
                unlink($imagenActual); // El directorio que contiene los archivos de imagen debe tener permisos de escritura
            }
            // Mensaje de éxito
            $_SESSION['mensaje'] = "Usuario eliminado exitosamente de la base de datos.";
            $_SESSION['tipo'] = "success";
            $_SESSION['icono'] = "check";
        } catch (PDOException $e) {
            $_SESSION['mensaje'] = "El Usuario no fue eliminado exitosamente. Error: " . $e->getMessage();
            $_SESSION['tipo'] = "danger";
            $_SESSION['icono'] = "ban";
        }
        redireccionar('usuarios');
    }
}
