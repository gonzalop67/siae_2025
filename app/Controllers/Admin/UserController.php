<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;

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
                ->paginate(5);
        } else {
            $users = $this->userModel
                ->orderBy('us_fullname')
                ->paginate(5);
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
        // 1. Validar datos de entrada utilizando tu método de validación estricta
        $input = filter_input_array(INPUT_POST, FILTER_DEFAULT) ?? [];

        if (!$this->userModel->validate($input)) {
            return json_encode([
                'ok' => false,
                'errors' => $this->userModel->errors
            ]);
        }

        // 2. Seguridad: Uso de hash para contraseñas
        $passwordHash = password_hash($input['us_password'] ?? '', PASSWORD_DEFAULT);

        // 3. Limpieza y normalización de textos
        $us_apellidos = preg_replace('/\s+/', ' ', trim($input['us_apellidos'] ?? ''));
        $us_nombres   = preg_replace('/\s+/', ' ', trim($input['us_nombres'] ?? ''));
        $us_fullname  = trim($us_apellidos . " " . $us_nombres);

        // 4. PLANIFICACIÓN DE LA IMAGEN: Calculamos el nombre que tendrá el archivo, pero NO lo subimos aún
        $imageName = 'default.png';
        $tieneImagen = (isset($_FILES['us_foto']) && $_FILES['us_foto']['error'] === UPLOAD_ERR_OK);

        if ($tieneImagen) {
            // Generamos un nombre único idéntico al que crearía tu método upload_image()
            // (Por ejemplo, usando la extensión original y un timestamp o hash único)
            $ext = pathinfo($_FILES['us_foto']['name'], PATHINFO_EXTENSION);
            $imageName = 'user_' . uniqid() . '_' . time() . '.' . $ext;
        }

        // 5. Preparación del set de datos (incluyendo el nombre planificado de la foto)
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
            'us_foto'               => $imageName, // Nombre guardado preventivamente
            'us_activo'             => $input['activo'] ?? '1',
        ];

        $perfiles = $input['perfiles'] ?? [];
        $rutaArchivoSubido = ''; // Guardará la ruta física si se llega a subir

        // 6. Persistencia con manejo de transacciones real
        try {
            // 1. INICIAR TRANSACCIÓN SQL
            $this->userModel->beginTransaction();

            // Ejecutamos la creación en la base de datos
            $usuario = $this->userModel->create($datos);

            // Captura del ID a través de tu método público
            $idUsuario = $this->userModel->getInsertId();
            if ($idUsuario === 0 && is_array($usuario)) {
                $idUsuario = (int)($usuario['id_usuario'] ?? $usuario['id'] ?? 0);
            }

            if ($idUsuario === 0) {
                throw new \Exception("Error al procesar el identificador único del nuevo registro.");
            }

            // 2. CARGA FÍSICA DE LA IMAGEN: Ahora que la base de datos aceptó el registro, subimos el archivo
            if ($tieneImagen) {
                // Construimos la ruta absoluta saliendo de App/Controllers/ hacia la raíz del proyecto
                // __DIR__ es "C:\xampp\htdocs\siae_2025\App\Controllers"
                // dirname(__DIR__, 2) nos sube dos niveles hasta "C:\xampp\htdocs\siae_2025"
                $raizProyecto = dirname(__DIR__, 2);
                $destino = $raizProyecto . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $imageName;

                if (move_uploaded_file($_FILES['us_foto']['tmp_name'], $destino)) {
                    $rutaArchivoSubido = $destino; // Guardamos la ruta absoluta por si hay que hacer unlink() en el catch
                } else {
                    throw new \Exception("No se pudo guardar físicamente la imagen en el servidor. Verifique permisos o rutas.");
                }
            }

            // 3. Sincronizar los roles (Bajo la misma transacción)
            $this->roleUserModel->sync($idUsuario, $perfiles);

            // 4. CONFIRMAR CAMBIOS SI TODO SALIÓ BIEN
            $this->userModel->commit();

            return json_encode([
                'ok' => true,
                'mensaje' => 'Usuario procesado con éxito.'
            ]);
        } catch (\Throwable $e) {
            // 5. REVERTIR TRANSACCIÓN SQL
            $this->userModel->rollBack();

            // LIMPIEZA DE BASURA: Si la imagen se alcanzó a subir pero la sincronización de perfiles
            // o el commit fallaron, borramos el archivo físico para no dejar rastro.
            if (!empty($rutaArchivoSubido) && file_exists($rutaArchivoSubido)) {
                unlink($rutaArchivoSubido);
            }

            return json_encode([
                'ok' => false,
                'mensaje' => "Ocurrió un error inesperado: " . $e->getMessage()
            ]);
        }
    }

    public function edit(int $id)
    {
        $title = "Editar Usuario";
        $usuario = $this->userModel->find($id);
        $roles = $this->roleModel->orderBy('pe_nombre')->get();
        $userRoles = $this->userModel->getRoleIds($id);

        return $this->view('admin.users.edit', compact('title', 'usuario', 'userRoles', 'roles'));
    }

    public function update(int $id)
    {
        // 1. Entrada de datos pura (Confiamos en Sentencias Preparadas en el modelo para evitar SQLi)
        $input = filter_input_array(INPUT_POST, FILTER_DEFAULT) ?? [];

        // Validación lógica en el modelo (Aquí validas formato de email, campos requeridos, etc.)
        if (!$this->userModel->validate($input, $id)) {
            return json_encode([
                'ok' => false,
                'errors' => $this->userModel->errors
            ]);
        }

        // 2. BUSQUEDA SEGURA: Obtenemos el usuario actual directo de la base de datos
        // Usamos first() o el método que use tu modelo para traer un solo registro por ID
        $usuarioActual = $this->userModel->find($id);

        // Extraemos el nombre real de la foto guardada en la base de datos (con su fallback)
        $fotoViejaBaseDatos = !empty($usuarioActual['us_foto']) ? $usuarioActual['us_foto'] : 'no-disponible.png';
        $image = $fotoViejaBaseDatos;

        if (isset($_FILES['us_foto']) && $_FILES['us_foto']['error'] === UPLOAD_ERR_OK) {
            // Subimos la nueva foto
            $nuevaImagen = $this->upload_image();

            if ($nuevaImagen) {
                // Si la subida tiene éxito, procedemos a borrar la foto vieja del disco
                // Evitamos borrar las imágenes genéricas del sistema
                if ($fotoViejaBaseDatos !== 'no-disponible.png' && $fotoViejaBaseDatos !== 'default.png') {
                    $rutaFotoVieja = dirname($_SERVER['SCRIPT_FILENAME']) . '/uploads/' . $fotoViejaBaseDatos;

                    if (file_exists($rutaFotoVieja)) {
                        unlink($rutaFotoVieja); // Borrado físico real en el servidor
                    }
                }
                // Asignamos el nuevo nombre para la base de datos
                $image = $nuevaImagen;
            }
        }

        // 3. Seguridad: Hash para contraseñas
        $passwordHash = null;
        if (!empty($input['us_password'])) {
            $passwordHash = password_hash($input['us_password'], PASSWORD_DEFAULT);
        }

        // 4. Limpieza y normalización de espacios en textos
        $us_apellidos = preg_replace('/\s+/', ' ', trim($input['us_apellidos'] ?? ''));
        $us_nombres   = preg_replace('/\s+/', ' ', trim($input['us_nombres'] ?? ''));
        $us_fullname  = trim($us_apellidos . " " . $us_nombres);

        // 5. Preparación del set de datos limpio
        $datos = [
            'institucion_id'          => 1,
            'us_titulo'               => trim($input['us_titulo'] ?? ''),
            'us_titulo_descripcion'   => trim($input['us_titulo_descripcion'] ?? ''),
            'us_apellidos'            => $us_apellidos,
            'us_nombres'              => $us_nombres,
            'us_email'                => trim($input['us_email'] ?? ''), // Se guardará como texto puro y válido
            'us_login'                => trim($input['us_login'] ?? ''),
            'us_shortname'            => trim($input['us_shortname'] ?? ''),
            'us_fullname'             => $us_fullname,
            'us_genero'               => $input['genero'] ?? 'M',
            'us_foto'                 => $image,
            'us_activo'               => $input['activo'] ?? '1',
        ];

        if ($passwordHash !== null) {
            $datos['us_password'] = $passwordHash;
        }

        $perfiles = $input['perfiles'] ?? [];

        // 6. Persistencia con manejo de transacciones atómicas
        try {
            $this->userModel->beginTransaction();
            // echo "<pre>"; print_r($datos); echo "</pre>"; die();

            // Ejecutar actualización
            $this->userModel->update($id, $datos);


            // Sincronizar roles bajo la misma transacción
            $this->roleUserModel->sync($id, $perfiles);

            // Confirmar cambios en la base de datos
            $this->userModel->commit();

            return json_encode([
                'ok' => true,
                'mensaje' => 'Usuario procesado con éxito.'
            ]);
        } catch (\Throwable $e) {
            // Deshace cualquier cambio si algo falla en el proceso
            $this->userModel->rollBack();

            return json_encode([
                'ok' => false,
                'mensaje' => "Ocurrió un error inesperado: " . $e->getMessage()
            ]);
        }
    }

    public function delete(int $id)
    {
        header('Content-Type: application/json');

        try {
            $eliminado = $this->userModel->delete($id);

            if ($eliminado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'El registro ha sido eliminado correctamente.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'No se encontró el registro o ya fue eliminado.'
                ]);
            }
        } catch (\Throwable $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ]);
        }
        exit; // Detiene la ejecución para que solo devuelva el JSON
    }

    // Método para restaurar un usuario (Botón Verde)
    public function restore(int $id)
    {
        header('Content-Type: application/json');
        try {
            // Llama al método restore() que añadimos en la clase Model
            $restaurado = $this->userModel->restore($id);

            if ($restaurado) {
                echo json_encode(['success' => true, 'message' => 'El usuario ha sido restaurado con éxito.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo restaurar el usuario.']);
            }
        } catch (\Throwable $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }

    // Método para eliminación física definitiva (Botón Rojo)
    public function destroy(int $id)
    {
        header('Content-Type: application/json');

        try {
            // 1. Buscamos al usuario en la base de datos antes de borrarlo
            $usuario = $this->userModel->withTrashed()->find($id);

            if (!$usuario) {
                echo json_encode([
                    'success' => false,
                    'titulo'  => 'Atención',
                    'mensaje' => 'El usuario no existe en el sistema.',
                    'estado'  => 'warning'
                ]);
                exit;
            }

            // Guardamos el nombre de la foto que está en la base de datos
            $fotoNombre = !empty($usuario['us_foto']) ? $usuario['us_foto'] : 'no-disponible.png';

            // 2. Ejecutamos la eliminación física definitiva en la base de datos
            $resultado = $this->userModel->forceDelete($id);

            if ($resultado) {
                // 3. Si se borró con éxito de la base de datos, procedemos a borrar el archivo físico
                if ($fotoNombre !== 'no-disponible.png' && $fotoNombre !== 'default.png') {
                    $rutaFisicaFoto = dirname($_SERVER['SCRIPT_FILENAME']) . '/uploads/' . $fotoNombre;

                    if (file_exists($rutaFisicaFoto)) {
                        unlink($rutaFisicaFoto);
                    }
                }

                echo json_encode([
                    'success' => true,
                    'titulo'  => '¡Completado!',
                    'mensaje' => 'El usuario ha sido eliminado permanentemente del sistema.',
                    'estado'  => 'success'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'titulo'  => 'Error',
                    'mensaje' => 'No se pudo eliminar el registro de la base de datos.',
                    'estado'  => 'error'
                ]);
            }
        } catch (\mysqli_sql_exception $e) {
            // CAPTURA EXITOSA: Ahora que removimos el die(), el catch atrapa el error 1451 perfectamente
            if ($e->getCode() === 1451) {
                echo json_encode([
                    'success' => false,
                    'titulo'  => 'No se puede eliminar',
                    'mensaje' => 'El usuario tiene registros vinculados. Debe reasignar o borrar esas dependencias antes de eliminarlo de forma definitiva.',
                    'estado'  => 'error'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'titulo'  => 'Error de Base de Datos',
                    'mensaje' => 'Fallo en la consulta: ' . $e->getMessage(),
                    'estado'  => 'error'
                ]);
            }
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'titulo'  => 'Error inesperado',
                'mensaje' => $e->getMessage(),
                'estado'  => 'error'
            ]);
        }
        exit;
    }

    public function wastebasket()
    {
        $title = "Papelera de Usuarios";
        $search = isset($_GET['search']) ? $_GET['search'] : "";

        // 1. Iniciamos el filtro de borrados lógicos
        $query = $this->userModel->onlyTrashed();

        if ($search !== "") {
            // Obtenemos dinámicamente el nombre de la tabla del modelo (ej: usuarios)
            $table = $this->userModel->getTable() ?? 'sw_usuario'; // Asegura tener un método getTable o pon el nombre de tu tabla directamente

            // 2. Asignamos el WHERE explícito calificando las columnas con su tabla
            $query->where = "({$table}.us_fullname LIKE ? OR {$table}.us_login LIKE ?)";

            $query->values = [
                '%' . $search . '%',
                '%' . $search . '%'
            ];
        }

        // 3. Ordenamos y paginamos de forma nativa
        $users = $query->orderBy('deleted_at', 'DESC')->paginate(5);

        // show($users);
        // die();

        // 4. Renderizamos la vista de la papelera
        return $this->view('admin.users.wastebasket', compact('users', 'title'));
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
