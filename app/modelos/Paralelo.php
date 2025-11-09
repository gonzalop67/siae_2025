<?php
class Paralelo
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function obtener($id_paralelo)
    {
        $this->db->query("SELECT * FROM sw_paralelo WHERE id_paralelo = $id_paralelo");
        return $this->db->registro();
    }

    public function obtenerParalelosVigentes()
    {
        $this->db->query("SELECT pa.id_paralelo, 
                                 pa_nombre, 
                                 pl.*, 
                                 mo_nombre, 
                                 sn.nombre, 
                                 cu_nombre, 
                                 es_figura, 
                                 jo_nombre 
                            FROM sw_paralelo pa, 
                                 sw_periodo_lectivo pl, 
                                 sw_modalidad mo,  
                                 sw_periodo_estado pe, 
                                 sw_curso_subnivel cs,
                                 sw_sub_nivel_educacion sn,  
                                 sw_curso cu, 
                                 sw_especialidad es, 
                                 sw_jornada jo  
                           WHERE pl.id_periodo_lectivo = pa.id_periodo_lectivo 
                             AND mo.id_modalidad = pl.id_modalidad  
                             AND pe.id_periodo_estado = pl.id_periodo_estado 
                             AND cs.id_curso_subnivel = pa.curso_subnivel_id
                             AND cu.id_curso = cs.curso_id
                             AND sn.id = cs.subnivel_id
                             AND es.id_especialidad = cs.especialidad_id
                             AND jo.id_jornada = pa.id_jornada
                             AND pe.pe_descripcion = 'ACTUAL'   
                           ORDER BY pa_orden ASC");
        return $this->db->registros();
    }

    public function obtenerParalelos($id_periodo_lectivo)
    {
        $this->db->query("
            SELECT p.*, 
                   cu_nombre,
                   cu_abreviatura, 
                   es_figura, 
                   es_abreviatura, 
                   jo_nombre 
              FROM sw_paralelo p,
                   sw_curso_subnivel cs,
                   sw_curso c, 
                   sw_especialidad e, 
                   sw_jornada j  
             WHERE cs.id_curso_subnivel = p.curso_subnivel_id
               AND c.id_curso = cs.curso_id  
               AND e.id_especialidad = cs.especialidad_id 
               AND j.id_jornada = p.id_jornada 
               AND p.id_periodo_lectivo = $id_periodo_lectivo 
             ORDER BY pa_orden              
        ");

        return $this->db->registros();
    }

    public function existeNombre($pa_nombre, $curso_subnivel_id, $jornada_id, $periodo_lectivo_id)
    {
        $this->db->query("SELECT * FROM sw_paralelo WHERE curso_subnivel_id = $curso_subnivel_id AND id_jornada = $jornada_id AND id_periodo_lectivo = $periodo_lectivo_id AND pa_nombre = '$pa_nombre'");

        return $this->db->rowCount() > 0;
    }

    public function contarEstudiantesPorGenero($id_paralelo)
    {
        $this->db->query("SELECT * FROM sw_def_genero ORDER BY id_def_genero");
        $registros = $this->db->registros();
        $cadena = "";
        $suma_generos = 0;
        if (count($registros) > 0) {
            foreach ($registros as $registro) {
                $genero = $registro->id_def_genero;
                $this->db->query("SELECT id_def_genero, 
                                         COUNT(*) AS numero 
                                    FROM sw_estudiante_periodo_lectivo ep, 
                                         sw_estudiante e 
                                   WHERE e.id_estudiante = ep.id_estudiante 
                                     AND ep.id_paralelo = $id_paralelo 
                                     AND activo = 1 
                                     AND id_def_genero = $genero 
                                   GROUP BY id_def_genero");
                $conteo = $this->db->registro();
                $numero = empty($conteo) ? 0 : $conteo->numero;
                if ($genero == 1) {
                    $cadena .= "&nbsp;Mujeres: " . $numero . ", ";
                } else {
                    $cadena .= "Hombres: " . $numero;
                }
                $suma_generos += $numero;
            }
            return $cadena . " - Total estudiantes: " . $suma_generos;
        } else {
            return "No se han matriculado estudiantes todavía...";
        }
    }

    public function insertar($datos)
    {
        $this->db->query("SELECT MAX(pa_orden) AS max_orden FROM sw_paralelo WHERE curso_subnivel_id = $datos[curso_subnivel_id] AND id_jornada = $datos[jornada_id] AND id_periodo_lectivo = $datos[periodo_lectivo_id]");
        $registro = $this->db->registro();

        $max_orden = empty($registro) ? 1 : $registro->max_orden + 1;

        $this->db->query("INSERT INTO sw_paralelo SET curso_subnivel_id = :curso_subnivel_id, id_jornada = :jornada_id, id_periodo_lectivo = :periodo_lectivo_id, pa_nombre = :pa_nombre, pa_orden = :pa_orden");

        //Vincular valores
        $this->db->bind(':pa_nombre', $datos['pa_nombre']);
        $this->db->bind(':jornada_id', $datos['jornada_id']);
        $this->db->bind(':curso_subnivel_id', $datos['curso_subnivel_id']);
        $this->db->bind(':periodo_lectivo_id', $datos['periodo_lectivo_id']);
        $this->db->bind(':pa_orden', $max_orden);

        $this->db->execute();
    }

    public function actualizar($datos)
    {
        $this->db->query("UPDATE sw_paralelo SET curso_subnivel_id = :curso_subnivel_id, id_jornada = :jornada_id, id_periodo_lectivo = :periodo_lectivo_id, pa_nombre = :pa_nombre WHERE id_paralelo = :id_paralelo");

        //Vincular valores
        $this->db->bind(':curso_subnivel_id', $datos['curso_subnivel_id']);
        $this->db->bind(':jornada_id', $datos['jornada_id']);
        $this->db->bind(':periodo_lectivo_id', $datos['periodo_lectivo_id']);
        $this->db->bind(':pa_nombre', $datos['pa_nombre']);
        $this->db->bind(':id_paralelo', $datos['id_paralelo']);

        $this->db->execute();
    }
}