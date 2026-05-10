<?php

namespace App\Controllers;

use App\Models\Institucion;
use App\Models\Perfil;
use App\Models\Usuario;
use App\Models\UsuarioPerfil;

use Core\Encrypter;

class LoginController extends Controller
{
    protected Perfil $perfilModel;
    protected Institucion $institucionModel;
    protected UsuarioPerfil $usuarioPerfil;
    protected Usuario $userModel;

    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->institucionModel = new Institucion;
        $this->usuarioPerfil = new UsuarioPerfil;
        $this->perfilModel = new Perfil;
        $this->userModel = new Usuario;
    }

    public function showLoginForm()
    {
        $institucion = $this->institucionModel
            ->select('in_nombre')
            ->orderBy('id_institucion')
            ->first();
        $nom_institucion = $institucion['in_nombre'];
        $perfiles = $this->perfilModel->orderBy('pe_nombre')->get();
        return $this->view('auth.login', compact('nom_institucion', 'perfiles'));
    }

    public function login()
    {
        $username = $_POST['usuario'];
        $password = $_POST['clave'];
        $id_perfil = $_POST['perfil'];

        // Verify data login
        $clave = Encrypter::encrypt($password);

        $usuario = $this->userModel
            ->where('us_login', $username)
            ->where('us_password', $clave)
            ->first();

        if (!empty($usuario)) {
            // Verificar si el perfil ingresado pertenece al usuario
            $id_usuario = $usuario['id_usuario'];
            $usuarioPerfil = $this->usuarioPerfil
                ->where('id_usuario', $id_usuario)
                ->where('id_perfil', $id_perfil)
                ->first();
            if (!empty($usuarioPerfil)) {
                // ASEGÚRATE DE QUE session_start() se ejecutó antes
                if (session_status() === PHP_SESSION_NONE) session_start();

                $_SESSION['authenticated'] = true;
                // $_SESSION['username'] = $usuario['username'];
                $perfil = $this->perfilModel->where('id_perfil', $id_perfil)->first();
                return json_encode([
                    'error' => false,
                    'slug' => $perfil['pe_slug'],
                ]);
            } else {
                return json_encode([
                    'error' => true,
                    'errors' => [
                        'mensaje' => 'El Usuario no tiene asignado el perfil seleccionado.'
                    ]
                ]);
            }
        } else {
            return json_encode([
                'error' => true,
                'errors' => [
                    'mensaje' => 'Usuario y/o password incorrectos.'
                ]
            ]);
        }
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = []; // Forma más segura de limpiar que session_unset()
        session_destroy();

        // Borrar la cookie de sesión del navegador (esto evita el "limbo" al reingresar)
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        redireccionar('/');
        exit();
    }
}
