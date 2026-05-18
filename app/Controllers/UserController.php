<?php

namespace App\Controllers;

use App\Models\Usuario;
use App\Models\Perfil;
use App\Models\UsuarioPerfil;

use Core\Encrypter;

class UserController extends Controller
{
    protected Usuario $userModel;
    protected Perfil $roleModel;
    protected UsuarioPerfil $roleUserModel;

    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->userModel = new Usuario;
        $this->roleModel = new Perfil;
        $this->roleUserModel = new UsuarioPerfil;
    }

    public function index()
    {
        $title = "Users Admin";

        $search = isset($_GET['search']) ? $_GET['search'] : "";

        if ($search !== "") {
            $users = $this->userModel
                ->where('us_fullname', 'LIKE', '%' . $_GET['search'] . '%')
                ->orWhere('us_login', 'LIKE', '%' . $_GET['search'] . '%')
                ->orderBy('us_fullname')
                ->paginate(6);
        } else {
            $users = $this->userModel
                ->orderBy('us_fullname')
                ->paginate(6);
        }

        // return $users;

        return $this->view('admin.users.index', compact('users', 'title'));
    }

    public function create()
    {
        $title = "Nuevo Usuario";
        $roles = $this->roleModel->orderBy('pe_nombre')->get();

        return $this->view('admin.users.create', compact('title', 'roles'));
    }

    public function upload_image()
    {
        $extension = explode('.', $_FILES['us_foto']['name']);
        $new_name = rand() . '.' . $extension[1];
        $destination = dirname(dirname(dirname(__FILE__))) . '/public/uploads/' . $new_name;
        move_uploaded_file($_FILES['us_foto']['tmp_name'], $destination);

        return $new_name;
    }

    public function store()
    {
        // 1. Validar datos de entrada utilizando un método de filtrado
        $input = filter_input_array(INPUT_POST, FILTER_DEFAULT) ?? [];

        if (!$this->userModel->validate($input)) {
            return json_encode([
                'ok' => false,
                'errors' => $this->userModel->errors
            ]);
        }

        // 2. Manejo seguro de la carga de imágenes
        $image = 'default.png';
        if (isset($_FILES['us_foto']) && $_FILES['us_foto']['error'] === UPLOAD_ERR_OK) {
            $image = $this->upload_image();
        }

        // 3. Seguridad: Uso de hash para contraseñas (NUNCA encriptar bidireccionalmente)
        $passwordHash = password_hash($input['us_password'] ?? '', PASSWORD_DEFAULT);

        // 4. Limpieza y normalización de textos
        $us_apellidos = preg_replace('/\s+/', ' ', trim($input['us_apellidos'] ?? ''));
        $us_nombres   = preg_replace('/\s+/', ' ', trim($input['us_nombres'] ?? ''));
        $us_fullname  = trim($us_apellidos . " " . $us_nombres);

        // 5. Preparación del set de datos
        $datos = [
            'institucion_id'        => 1,
            'us_titulo'             => trim($input['us_titulo'] ?? ''),
            'us_titulo_descripcion' => trim($input['us_titulo_descripcion'] ?? ''),
            'us_apellidos'          => $us_apellidos,
            'us_nombres'            => $us_nombres,
            'us_email'              => trim($input['us_email'] ?? ''),
            'us_login'              => trim($input['us_login'] ?? ''),
            'us_password'           => $passwordHash,
            'us_shortname'          => trim($input['us_shortname'] ?? ''),
            'us_fullname'           => $us_fullname,
            'us_genero'             => $input['genero'] ?? 'M',
            'us_foto'               => $image,
            'us_activo'             => $input['activo'] ?? '1',
        ];

        // return $datos;

        $perfiles = $input['perfiles'] ?? [];

        // 6. Persistencia con manejo de transacciones real
        try {
            // 1. INICIAR TRANSACCIÓN (Ahora ambas conexiones apuntan al mismo objeto mysqli)
            $this->userModel->beginTransaction();

            $usuario = $this->userModel->create($datos);

            // Validar estrictamente la respuesta del modelo
            if (empty($usuario) || !isset($usuario['id_usuario'])) {
                throw new \Exception("Error al insertar el registro de usuario.");
            }

            // 2. Sincronizar los roles (Se ejecuta bajo la misma transacción)
            $this->roleUserModel->sync($usuario['id_usuario'], $perfiles);

            // 3. CONFIRMAR CAMBIOS SI TODO SALIÓ BIEN
            $this->userModel->commit();

            return json_encode([
                'ok' => true,
                'mensaje' => 'Usuario procesado con éxito.'
            ]);
        } catch (\Throwable $e) {
            // 4. REVERTIR ABSOLUTAMENTE TODO EN CASO DE ERROR DE SQL O EXCEPCIÓN PHP
            $this->userModel->rollBack();

            return json_encode([
                'ok' => false,
                'mensaje' => "Ocurrió un error inesperado: " . $e->getMessage()
            ]);
        }
    }

    public function roles(int $id)
    {
        // 1. El usuario que estamos editando
        $user = $this->userModel->find($id);

        // 2. TODOS los roles que existen en el sistema (para los checkboxes)
        // Asumo que tienes un roleModel o tabla 'roles'
        $roles = $this->roleModel
            ->orderBy('pe_nombre')
            ->get();

        // 3. Los IDs de los roles que este Usuario ya tiene asignados
        // Esta es la simulación real de: $user->roles->pluck('id')->toArray();
        $userRoles = $this->userModel->getRoleIds($id);

        $title = "Asignación de Roles";

        return $this->view('admin.users.roles', compact('title', 'user', 'roles', 'userRoles'));
    }

    public function updateRoles(int $id)
    {
        // $id es el id del usuario
        $RoleIds = $_POST['roles'];
        $this->roleUserModel->sync($id, $RoleIds);

        // Mensaje de éxito
        $_SESSION['mensaje'] = "Roles actualizados satisfactoriamente.";
        $_SESSION['tipo'] = "success";
        $_SESSION['icono'] = "check";
        redireccionar('/users/' . $id . '/roles');
    }
}
