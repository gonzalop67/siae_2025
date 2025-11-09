<?php
class Estudiante
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function contarEstudiantes($id_periodo_lectivo)
    {
        $this->db->query("SELECT COUNT(*) AS nro_estudiantes
                            FROM sw_estudiante e, 
                                 sw_estudiante_periodo_lectivo ep
                           WHERE e.id_estudiante = ep.id_estudiante
                             AND activo = 1 
                             AND id_periodo_lectivo = $id_periodo_lectivo");
        return $this->db->registro()->nro_estudiantes;
    }

    public function CalculaEdad($fecha)
    {
        list($Y, $m, $d) = explode("-", $fecha);
        return (date("md") < $m . $d ? date("Y") - $Y - 1 : date("Y") - $Y);
    }

    public function existeNombreEstudiante($apellidos, $nombres)
    {
        $this->db->query("SELECT * 
                            FROM sw_estudiante 
                           WHERE es_apellidos = '$apellidos'
                             AND es_nombres = '$nombres'
        ");
        $this->db->registros();

        return $this->db->rowCount() > 0;
    }

    public function existeNroCedula($es_cedula)
    {
        $this->db->query("SELECT * 
                            FROM sw_estudiante 
                           WHERE es_cedula = '$es_cedula'
        ");
        $this->db->registros();

        return $this->db->rowCount() > 0;
    }

    public function existeEstudiantePeriodoLectivo($apellidos, $nombres)
    {
        $this->db->query("SELECT e.id_estudiante 
                            FROM sw_estudiante e,
                                 sw_estudiante_periodo_lectivo ep, 
                                 sw_periodo_lectivo pl, 
                                 sw_periodo_estado pe   
                           WHERE e.id_estudiante = ep.id_estudiante 
                             AND pl.id_periodo_lectivo = ep.id_periodo_lectivo 
                             AND pe.id_periodo_estado = pl.id_periodo_estado 
                             AND e.es_apellidos = '$apellidos' 
                             AND e.es_nombres = '$nombres' 
                             AND pe.pe_descripcion = 'ACTUAL'
        ");
        $this->db->registros();

        return $this->db->rowCount() > 0;
    }

    public function listarEstudiantesParalelo($id_paralelo)
    {
        $query = "SELECT e.*, 
                         ep.id_estudiante_periodo_lectivo, 
                         nro_matricula, 
                         dg_nombre, 
                         pe_descripcion, 
                         es_retirado 
                    FROM sw_estudiante e, 
                         sw_estudiante_periodo_lectivo ep, 
                         sw_periodo_lectivo p, 
                         sw_periodo_estado pe, 
                         sw_def_genero dg 
                   WHERE e.id_estudiante = ep.id_estudiante 
                         AND p.id_periodo_lectivo = ep.id_periodo_lectivo 
                         AND pe.id_periodo_estado = p.id_periodo_estado 
                         AND dg.id_def_genero = e.id_def_genero 
                         AND ep.id_paralelo = $id_paralelo 
                         AND activo = 1 
                   ORDER BY es_apellidos, es_nombres ASC";

        $this->db->query($query);

        $registros = $this->db->registros();
        $num_rows = count($registros);
        $cadena = "";

        if ($num_rows > 0) {
            $contador = 0;
            foreach ($registros as $row) {
                $contador++;
                $codigo = $row->id_estudiante;
                $id_estudiante_periodo_lectivo = $row->id_estudiante_periodo_lectivo;
                $apellidos = $row->es_apellidos;
                $nombres = $row->es_nombres;
                $nro_matricula = $row->nro_matricula;
                $cedula = $row->es_cedula;
                $fec_nacim = $row->es_fec_nacim;
                $edad = $this->CalculaEdad($fec_nacim);
                $genero = $row->dg_nombre;
                $retirado = $row->es_retirado;
                $checked = ($retirado == "N") ? "" : "checked";
                $estado = $row->pe_descripcion;
                $disabled = ($estado == "TERMINADO") ? "disabled" : "";
                $fondolinea = ($contador % 2 == 0) ? "itemParTabla" : "itemImparTabla";
                $cadena .= "<tr class=\"$fondolinea\" onmouseover=\"className='itemEncimaTabla'\" onmouseout=\"className='$fondolinea'\">\n";
                $cadena .= "<td>$contador</td>\n";
                $cadena .= "<td>$codigo</td>\n";
                $cadena .= "<td>$nro_matricula</td>\n";
                $cadena .= "<td>$apellidos</td>\n";
                $cadena .= "<td>$nombres</td>\n";
                $cadena .= "<td>$cedula</td>\n";
                $cadena .= "<td>$fec_nacim</td>\n";
                $cadena .= "<td>$edad</td>\n";
                $cadena .= "<td>$genero</td>\n";
                $cadena .= "<td><input type=\"checkbox\" name=\"chkretirado_" . $contador . "\" $checked $disabled onclick=\"actualizar_estado_retirado(this," . $codigo . ")\"></td>\n";
                $cadena .= "<td>\n";
                $cadena .= "<div class='btn-group'>\n";
                $cadena .= "<a href='javascript:;' class='btn btn-warning btn-sm item-edit' data=" . $codigo . " title='Editar'><span class='fas fa-pencil'></span></a>\n";
                if ($estado != "TERMINADO") {
                    $cadena .= "<a href='" . RUTA_URL . "/matriculacion/delete' class='btn btn-danger btn-sm item-delete' data=" . $codigo . " title='Quitar'><span class='fas fa-remove'></span></a>\n";
                    $cadena .= "<a href='" . RUTA_URL . "/matriculacion/certificado/$id_estudiante_periodo_lectivo' target='_blank' class='btn btn-info btn-sm' title='Certificado'><span class='far fa-file-text'></span></a>\n";
                }
                $cadena .= "</div>\n";
                $cadena .= "</td>\n";
                $cadena .= "</tr>\n";
            }
        } else {
            $cadena .= "<tr>\n";
            $cadena .= "<td colspan='100%' class='text-center'>No se han matriculado estudiantes para este paralelo...</td>\n";
            $cadena .= "</tr>\n";
        }

        return $cadena;
    }

    public function insertarEstudiante($datos)
    {
        $this->db->query("INSERT INTO sw_estudiante
                                  SET id_tipo_documento = :id_tipo_documento,
                                      id_def_genero = :id_def_genero,
                                      id_def_nacionalidad = :id_def_nacionalidad,
                                      es_apellidos = :es_apellidos,
                                      es_nombres = :es_nombres,
                                      es_nombre_completo = :es_nombre_completo,
                                      es_cedula = :es_cedula,
                                      es_email = :es_email,
                                      es_sector = :es_sector,
                                      es_direccion = :es_direccion,
                                      es_telefono = :es_telefono,
                                      es_fec_nacim = :es_fec_nacim");

        // Vincular valores
        $this->db->bind('id_tipo_documento', $datos['id_tipo_documento']);
        $this->db->bind('id_def_genero', $datos['id_def_genero']);
        $this->db->bind('id_def_nacionalidad', $datos['id_def_nacionalidad']);
        $this->db->bind('es_apellidos', $datos['es_apellidos']);
        $this->db->bind('es_nombres', $datos['es_nombres']);
        $this->db->bind('es_nombre_completo', $datos['es_nombre_completo']);
        $this->db->bind('es_cedula', $datos['es_cedula']);
        $this->db->bind('es_email', $datos['es_email']);
        $this->db->bind('es_sector', $datos['es_sector']);
        $this->db->bind('es_direccion', $datos['es_direccion']);
        $this->db->bind('es_telefono', $datos['es_telefono']);
        $this->db->bind('es_fec_nacim', $datos['es_fec_nacim']);

        $this->db->execute();

        $id_periodo_lectivo = $_SESSION['id_periodo_lectivo'];

        //Insertar en la tabla sw_estudiante_periodo_lectivo
        $this->db->query("SELECT MAX(id_estudiante) AS insertID FROM sw_estudiante");
        $registro = $this->db->registro();

        $id_estudiante = $registro->insertID;

        $this->db->query("SELECT MAX(nro_matricula) AS max_nro_matricula FROM sw_estudiante_periodo_lectivo WHERE id_periodo_lectivo = $id_periodo_lectivo");
        $record = $this->db->registro();
        $max_nro_matricula = ($this->db->rowCount() == 0) ? 1 : $record->max_nro_matricula + 1;

        $datos = [
            'id_estudiante' => $id_estudiante,
            'id_periodo_lectivo' => $id_periodo_lectivo,
            'id_paralelo' => $_POST['id_paralelo'],
            'es_estado' => 'N',
            'es_retirado' => 'N',
            'nro_matricula' => $max_nro_matricula,
            'activo' => 1
        ];

        $this->db->query("INSERT INTO sw_estudiante_periodo_lectivo
                                  SET id_estudiante = :id_estudiante,
                                      id_periodo_lectivo = :id_periodo_lectivo,
                                      id_paralelo = :id_paralelo,
                                      es_estado = :es_estado,
                                      es_retirado = :es_retirado,
                                      nro_matricula = :nro_matricula,
                                      activo = :activo");

        // Vincular valores
        $this->db->bind('id_estudiante', $datos['id_estudiante']);
        $this->db->bind('id_periodo_lectivo', $datos['id_periodo_lectivo']);
        $this->db->bind('id_paralelo', $datos['id_paralelo']);
        $this->db->bind('es_estado', $datos['es_estado']);
        $this->db->bind('es_retirado', $datos['es_retirado']);
        $this->db->bind('nro_matricula', $datos['nro_matricula']);
        $this->db->bind('activo', $datos['activo']);

        $this->db->execute();
    }
}
