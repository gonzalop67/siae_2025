<?php
class Instituciones extends Controlador
{
    private $institucionModelo;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        $this->institucionModelo = $this->modelo('Institucion');
    }

    public function index()
    {
        $instituciones = $this->institucionModelo->obtenerInstituciones();
        $datos = [
            'titulo' => 'Institución CRUD',
            'dashboard' => 'Admin',
            'instituciones' => $instituciones,
            'nombreVista' => 'admin/instituciones/index.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function edit($id)
    {
        $institucion = $this->institucionModelo->obtenerInstitucion($id);
        $administrador = $this->institucionModelo->obtenerAdministrador($id);
        $admin_list = $this->institucionModelo->obtenerPosiblesAdministradores($id);

        //
        // print_r("<pre>");
        // print_r($admin_list);
        // print_r("</pre>");
        // die();

        $datos = [
            'titulo' => 'Institución Edit',
            'dashboard' => 'Admin',
            'institucion' => $institucion,
            'administrador' => $administrador,
            'admin_list' => $admin_list,
            'nombreVista' => 'admin/instituciones/edit.php'
        ];
        $this->vista('admin/index', $datos);
    }

    public function upload_image()
    {
        if (isset($_FILES["logo"])) {
            $extension = explode('.', $_FILES['logo']['name']);
            $new_name = rand() . '.' . $extension[1];
            $destination = dirname(dirname(dirname(__FILE__))) . '/public/uploads/' . $new_name;
            move_uploaded_file($_FILES['logo']['tmp_name'], $destination);
            return $new_name;
        }
    }

    public function update()
    {
        $id_institucion = $_POST['id_institucion'];
        $admin_id = $_POST['admin_id'];
        $in_nombre = trim($_POST['nombre']);
        $in_direccion = preg_replace('/\s+/', ' ', trim($_POST['direccion']));
        $in_email = trim($_POST['email']);
        $in_telefono = trim($_POST['telefono']);
        $in_regimen = trim($_POST['regimen']);
        $in_nom_rector = preg_replace('/\s+/', ' ', trim($_POST['rector']));
        $in_genero_rector = $_POST['rector_genero'];
        $in_nom_vicerrector = preg_replace('/\s+/', ' ', trim($_POST['vicerrector']));
        $in_genero_vicerrector = $_POST['vicerrector_genero'];
        $in_nom_secretario = preg_replace('/\s+/', ' ', trim($_POST['secretario']));
        $in_genero_secretario = $_POST['secretario_genero'];
        $in_url = trim($_POST['url']);
        $in_amie = trim($_POST['amie']);
        $in_ciudad = trim($_POST['ciudad']);

        $in_copiar_y_pegar = $_POST['copiar_y_pegar'];

        $institucionActual = $this->institucionModelo->obtenerInstitucion($id_institucion);
        $in_logo = $institucionActual->in_logo;

        $ok = false;
        $titulo = "";
        $mensaje = "";
        $tipo_mensaje = "";

        if (!filter_var($in_email, FILTER_VALIDATE_EMAIL)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "El email es inválido.";
            $tipo_mensaje = "error";
        } else if ($institucionActual->in_nombre != $in_nombre && $this->institucionModelo->existeNombreInstitucion($in_nombre)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Nombre [$in_nombre] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else if ($institucionActual->in_email != $in_email && $this->institucionModelo->existeEmailInstitucion($in_email)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el Email [$in_email] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else if ($institucionActual->in_url != $in_url && $this->institucionModelo->existeURLInstitucion($in_url)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe la URL [$in_url] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else if ($institucionActual->in_amie != $in_amie && $this->institucionModelo->existeAMIEInstitucion($in_amie)) {
            $ok = false;
            $titulo = "Error";
            $mensaje = "Ya existe el código AMIE [$in_amie] en la Base de Datos.";
            $tipo_mensaje = "error";
        } else {

            if ($_FILES['logo']['name'] != "") {
                // Elimino el archivo de imagen anterior si existe
                $ruta = dirname(dirname(dirname(__FILE__))) . "/public/uploads/";
                $imagenActual = $ruta . $in_logo;

                if (file_exists($imagenActual)) {
                    unlink($imagenActual); // El directorio que contiene los archivos de imagen debe tener permisos de escritura
                }

                $image = $this->upload_image();
            } else {
                $image = $in_logo;
            }

            $datos = [
                'id_institucion' => $id_institucion,
                'admin_id' => $admin_id,
                'in_nombre' => $in_nombre,
                'in_direccion' => $in_direccion,
                'in_telefono' => $in_telefono,
                'in_regimen' => $in_regimen,
                'in_nom_rector' => $in_nom_rector,
                'in_genero_rector' => $in_genero_rector,
                'in_nom_vicerrector' => $in_nom_vicerrector,
                'in_genero_vicerrector' => $in_genero_vicerrector,
                'in_nom_secretario' => $in_nom_secretario,
                'in_genero_secretario' => $in_genero_secretario,
                'in_email' => $in_email,
                'in_url' => $in_url,
                'in_logo' => $image,
                'in_amie' => $in_amie,
                'in_ciudad' => $in_ciudad,
                'in_copiar_y_pegar' => $in_copiar_y_pegar
            ];

            //
        // print_r("<pre>");
        // print_r($datos);
        // print_r("</pre>");
        // die();

            try {
                $this->institucionModelo->actualizarInstitucion($datos);
                $ok = true;
                $_SESSION['mensaje'] = "La Institución fue actualizada exitosamente.";
                $_SESSION['tipo'] = "success";
                $_SESSION['icono'] = "check";
            } catch (PDOException $ex) {
                $ok = false;
                $titulo = "Error";
                $mensaje = "La Institución no fue actualizada exitosamente. Error: " . $ex->getMessage();
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
