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

    public function existeDNI($dni)
    {
        $this->db->query("SELECT * FROM sw_estudiante WHERE es_cedula = '$dni'");
        $this->db->registro();
        
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
                $cadena .= "<a href='javascript:;' class='btn btn-warning btn-sm item-edit' data=" . $codigo . " title='Editar'><span class='fa fa-pencil'></span></a>\n";
                if ($estado != "TERMINADO") {
                    $cadena .= "<a href='" . RUTA_URL . "/matriculacion/delete' class='btn btn-danger btn-sm item-delete' data=" . $codigo . " title='Quitar'><span class='fa fa-remove'></span></a>\n";
                    $cadena .= "<a href='" . RUTA_URL . "/matriculacion/certificado/$id_estudiante_periodo_lectivo' target='_blank' class='btn btn-info btn-sm' title='Certificado'><span class='fa fa-file-text'></span></a>\n";
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
}
