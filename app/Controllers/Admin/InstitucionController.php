<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;

use App\Models\Institucion;

class InstitucionController extends Controller
{
    protected Institucion $institucionModel;

    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->institucionModel = new Institucion;
    }

    public function index()
    {
        $title = "Datos de la Institución";
        $institucion = $this->institucionModel->find(1);
        $admin_list = $this->institucionModel->obtenerPosiblesAdministradores();

        return $this->view('admin.institucion.index', compact('title', 'institucion', 'admin_list'));
    }

    public function update()
    {
        header('Content-Type: application/json');

        $response = ['success' => false, 'message' => '', 'errors' => []];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Sanitización de Campos de Texto
            $nombre    = trim($_POST['nombre'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');
            $telefono  = trim($_POST['telefono'] ?? '');
            $email     = trim($_POST['email'] ?? '');
            $admin_id  = trim($_POST['admin_id'] ?? '');
            $regimen   = mb_strtoupper(trim($_POST['regimen'] ?? ''), 'UTF-8');
            $nom_rector = trim($_POST['nom_rector'] ?? '');
            $genero_rector = trim($_POST['genero_rector'] ?? 'F');
            $nom_vicerrector = trim($_POST['nom_vicerrector'] ?? '');
            $genero_vicerrector = trim($_POST['genero_vicerrector'] ?? 'F');
            $nom_secretario = trim($_POST['nom_secretario'] ?? '');
            $genero_secretario = trim($_POST['genero_secretario'] ?? 'F');
            $url       = trim($_POST['url'] ?? '');
            $amie      = trim($_POST['amie'] ?? '');
            $ciudad    = trim($_POST['ciudad'] ?? '');
            $copiar_y_pegar = intval($_POST['copiar_y_pegar'] ?? 0);

            // Recuperar el id del registro que se va a actualizar
            $id = intval($_POST['id'] ?? 0);

            // Recuperar el logo actual por si no se sube uno nuevo (Evita sobreescribir con NULL)
            $logo_antiguo = trim($_POST['in_logo_file'] ?? '');
            $nombre_archivo_final = !empty($logo_antiguo) ? basename($logo_antiguo) : null;

            // 2. Validación básica obligatoria
            if (empty($nombre)) {
                $response['errors']['nombre'] = 'El nombre es requerido.';
            }
            if (empty($direccion)) {
                $response['errors']['direccion'] = 'La dirección es requerida.';
            }
            if ($id <= 0) {
                $response['errors']['id'] = 'Identificador de registro no válido.';
            }

            if (!empty($response['errors'])) {
                $response['message'] = 'Por favor, corrija los campos marcados.';
                echo json_encode($response);
                return;
            }

            try {
                // 3. Procesamiento y subida del Logotipo
                if (isset($_FILES['in_logo']) && $_FILES['in_logo']['error'] === UPLOAD_ERR_OK) {
                    $fileTmpPath = $_FILES['in_logo']['tmp_name'];
                    $fileName    = $_FILES['in_logo']['name'];
                    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                    $extensions_validas = ['jpg', 'jpeg', 'png', 'svg'];
                    if (in_array($fileExtension, $extensions_validas)) {

                        $uploadFileDir = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/';
                        $nuevo_nombre = 'logo_' . time() . '_' . uniqid() . '.' . $fileExtension;
                        $dest_path = $uploadFileDir . $nuevo_nombre;

                        if (move_uploaded_file($fileTmpPath, $dest_path)) {

                            // Borrar archivo antiguo de forma segura si existía uno previo
                            if (!empty($logo_antiguo)) {
                                $archivo_a_borrar = $uploadFileDir . basename($logo_antiguo);

                                if (file_exists($archivo_a_borrar) && is_file($archivo_a_borrar)) {
                                    unlink($archivo_a_borrar);
                                }
                            }

                            // Actualizar la variable con el nuevo nombre
                            $nombre_archivo_final = $nuevo_nombre;
                        }
                    } else {
                        $response['errors']['in_logo'] = 'Formato de imagen no permitido.';
                        echo json_encode($response);
                        return;
                    }
                }

                // 4. Mapeo de datos para el ORM
                $datos = [
                    'admin_id' => $admin_id,
                    'in_nombre' => $nombre,
                    'in_direccion' => $direccion,
                    'in_telefono' => $telefono,
                    'in_regimen' => $regimen,
                    'in_nom_rector' => $nom_rector,
                    'in_genero_rector' => $genero_rector,
                    'in_nom_vicerrector' => $nom_vicerrector,
                    'in_genero_vicerrector' => $genero_vicerrector,
                    'in_nom_secretario' => $nom_secretario,
                    'in_genero_secretario' => $genero_secretario,
                    'in_email' => $email,
                    'in_url' => $url,
                    'in_amie' => $amie,
                    'in_ciudad' => $ciudad,
                    'in_copiar_y_pegar' => $copiar_y_pegar
                ];

                // Inyectar el logo únicamente si se procesó un archivo nuevo
                if (isset($_FILES['in_logo']) && $_FILES['in_logo']['error'] === UPLOAD_ERR_OK) {
                    $datos['in_logo'] = $nombre_archivo_final;
                }

                // 5. Ejecución segura mediante tu ORM personalizado
                $actualizado = $this->institucionModel->update($id, $datos);

                if ($actualizado) {
                    $response['success'] = true;
                    $response['message'] = 'Registro actualizado correctamente.';
                } else {
                    $response['message'] = 'No se realizaron cambios o el registro no existe.';
                }
            } catch (\mysqli_sql_exception $e) {
                // Captura errores específicos de base de datos gracias a tu nuevo método query()
                $response['message'] = 'Error en la base de datos: ' . $e->getMessage();
            } catch (\Exception $e) {
                // Captura errores generales de PHP (como fallos al mover archivos)
                $response['message'] = 'Error al procesar la solicitud: ' . $e->getMessage();
            }
        }

        echo json_encode($response);
        return;
    }
}
