<?php

namespace App\Models;

class Usuario extends Model
{
    protected string $table = 'sw_usuario';
    protected string $primaryKey = 'id_usuario';
    protected array $fillable = [
        'institucion_id',
        'us_titulo',
        'us_titulo_descripcion',
        'us_apellidos',
        'us_nombres',
        'us_shortname',
        'us_fullname',
        'us_login',
        'us_email',
        'us_password',
        'request_password',
        'token_password',
        'expired_session',
        'us_foto',
        'us_genero',
        'us_activo'
    ];

    // CORRECCIÓN: Agregamos el parámetro $id para identificar si es una actualización
    public function validate(array $data, $id = null)
    {
        $this->errors = [];
        $is_updating = ($id !== null); // Determina si estamos editando

        // 1. Recuperar y limpiar datos del FormData
        $us_titulo             = trim($data['us_titulo'] ?? '');
        $us_titulo_descripcion = trim($data['us_titulo_descripcion'] ?? '');
        $us_apellidos          = preg_replace('/\s+/', ' ', trim($data['us_apellidos'] ?? ''));
        $us_nombres            = preg_replace('/\s+/', ' ', trim($data['us_nombres'] ?? ''));
        $us_shortname          = trim($data['us_shortname'] ?? '');
        $us_login              = preg_replace('/\s+/', ' ', trim($data['us_login'] ?? ''));
        $us_email              = trim($data['us_email'] ?? '');
        $us_password           = trim($data['us_password'] ?? '');
        $perfiles              = $data['perfiles'] ?? [];

        // 2. Generación de nombres
        $apellidos = explode(" ", $us_apellidos);
        $nombres   = explode(" ", $us_nombres);

        if ($us_shortname !== "") {
            $us_shortname = preg_replace('/\s+/', ' ', $us_shortname);
        } else {
            $primer_nombre   = $nombres[0] ?? '';
            $primer_apellido = $apellidos[0] ?? '';
            $us_shortname    = trim($us_titulo . " " . $primer_nombre . " " . $primer_apellido);
        }

        $us_fullname = trim($us_apellidos . " " . $us_nombres);

        // 3. Bloque de Validaciones

        // Título
        if (empty($us_titulo)) {
            $this->errors['us_titulo'] = "El campo Título es obligatorio.";
        } elseif (!preg_match('/^[a-zA-Z\.]{3,7}$/', $us_titulo)) {
            $this->errors['us_titulo'] = "La abreviatura del título tiene que ser de 3 a 7 caracteres y solo puede contener caracteres alfabéticos y el punto.";
        }

        // Descripción del Título
        if (empty($us_titulo_descripcion)) {
            $this->errors['us_titulo_descripcion'] = "El campo Descripción del Título es obligatorio.";
        } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s\.\,\-\_\:\;\(\)\n]{4,500}$/u', $us_titulo_descripcion)) {
            $this->errors['us_titulo_descripcion'] = "La descripción del título tiene que ser de 4 a 500 caracteres.";
        }

        // Apellidos
        if (empty($us_apellidos)) {
            $this->errors['us_apellidos'] = "El campo Apellidos es obligatorio.";
        } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s]{3,32}$/u', $us_apellidos)) {
            $this->errors['us_apellidos'] = "Los apellidos del usuario deben contener de 3 a 32 caracteres alfabéticos incluyendo acentos.";
        }

        // Nombres
        if (empty($us_nombres)) {
            $this->errors['us_nombres'] = "El campo Nombres es obligatorio.";
        } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s]{3,32}$/u', $us_nombres)) {
            $this->errors['us_nombres'] = "Los nombres del usuario deben contener de 3 a 32 caracteres alfabéticos incluyendo acentos.";
        }

        // Nombre Completo Único (Ignorar el registro actual si es actualización)
        // ADAPTACIÓN: Pasa el $id al método exists si tu base de datos lo soporta (ej: para hacer WHERE id != $id)
        if (!empty($us_fullname) && $this->exists('us_fullname', $us_fullname, $id)) {
            $this->errors['us_fullname'] = "Ya existe el nombre completo del usuario.";
        }

        // Login / Usuario
        if (empty($us_login)) {
            $this->errors['us_login'] = "El campo Usuario es obligatorio.";
        } elseif (!preg_match('/^[a-zA-Z0-9\_\-]{4,16}$/', $us_login)) {
            $this->errors['us_login'] = "El Nombre de Usuario debe tener entre 4 y 16 caracteres sin espacios en blanco.";
        } elseif ($this->exists('us_login', $us_login, $id)) { // ADAPTACIÓN: Validación con exclusión de ID
            $this->errors['us_login'] = "Ya existe el Nombre de Usuario en la base de datos.";
        }

        // Email
        if (empty($us_email)) {
            $this->errors['us_email'] = "El campo Email es obligatorio.";
        } elseif (!filter_var($us_email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['us_email'] = "El correo electrónico ingresado no es válido.";
        } elseif ($this->exists('us_email', $us_email, $id)) { // ADAPTACIÓN: Validación con exclusión de ID
            $this->errors['us_email'] = "Ya existe el correo electrónico.";
        }

        // Password (ADAPTACIÓN CRÍTICA PARA ACTUALIZACIÓN)
        if (!$is_updating && empty($us_password)) {
            // En creación, es obligatorio
            $this->errors['us_password'] = 'El campo Password es obligatorio.';
        } elseif (!empty($us_password)) {
            // Si no está vacío (ya sea en creación o si el usuario decidió cambiarlo en actualización), evaluamos la fuerza
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&+])[A-Za-z\d$@$!%*?&+]{8,}$/', $us_password)) {
                $this->errors['us_password'] = 'Contraseña débil [Mínimo 8 caracteres, mayúscula, minúscula, número y símbolo($, @, !, %, *, ?, &, +)].';
            }
        }

        // Checkboxes (Perfiles)
        if (!is_array($perfiles) || count($perfiles) === 0) {
            $this->errors['perfiles'] = "Debe asignar al menos un perfil al usuario.";
        }

        // Validación de la imagen de usuario, si se ha elegido alguna
        // 1. Verificar si el usuario seleccionó y subió un archivo sin errores
        
        if (isset($_FILES['us_foto']) && $_FILES['us_foto']['error'] === UPLOAD_ERR_OK) {
            // $fileTmpPath = $_FILES['us_foto']['tmp_name'];
            $fileName    = $_FILES['us_foto']['name'];
            $fileSize    = $_FILES['us_foto']['size'];
            // $fileType    = $_FILES['us_foto']['type'];

            // Validar extensión por seguridad (Evitar que suban scripts maliciosos .php)
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($fileExtension, $extensionesPermitidas)) {
                $this->errors['us_foto'] = "El formato de la imagen no es válido (Solo JPG, PNG, GIF, WEBP).";
            } elseif ($fileSize > 2 * 1024 * 1024) { // Limitar a 2MB
                $this->errors['us_foto'] = "La imagen es muy pesada. Máximo permitido: 2MB.";
            } //else {
                // Generar un nombre único para la foto para que no se duplique en el servidor
                /*$new_name = md5(time() . $fileName) . '.' . $fileExtension;
                $destination = dirname(dirname(dirname(__FILE__))) . '/public/uploads/' . $new_name;
                $this->errors['us_foto'] = $destination;
            }*/
        }

        return empty($this->errors);
    }

    public function getRoleIds(string $userId)
    {
        $sql = "SELECT id_perfil FROM sw_usuario_perfil WHERE id_usuario = ?";
        $data = $this->query($sql, [$userId])->get();

        // Aquí es donde simulamos el pluck('id')->toArray()
        return array_column($data, 'id_perfil');
    }
}
