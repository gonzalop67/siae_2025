<?php
class Curso
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function obtenerCursos()
    {
        $this->db->query("SELECT c.*,
                                 id_curso_subnivel,  
                                 sn.nombre, 
                                 e.es_figura, 
                                 cs.orden 
                            FROM sw_curso c, 
                                 sw_especialidad e,  
                                 sw_sub_nivel_educacion sn, 
                                 sw_curso_subnivel cs  
                           WHERE e.id_especialidad = cs.especialidad_id
                             AND c.id_curso = cs.curso_id  
                             AND sn.id = cs.subnivel_id");
        return $this->db->registros();
    }

    public function obtenerSubnivelesCurso($id_curso)
    {
        $this->db->query("SELECT * FROM sw_curso_subnivel WHERE curso_id = $id_curso");
        $registros = $this->db->registros();
        $array = array();
        foreach ($registros as $r) {
            array_push($array, $r->subnivel_id);
        }
        return $array;
    }

    public function obtener($id_curso)
    {
        $this->db->query("SELECT * FROM sw_curso WHERE id_curso = $id_curso");
        return $this->db->registro();
    }

    public function existeCampo($columna, $value)
    {
        return $this->db->existValueColumn('sw_curso', $columna, $value);
    }

    public function insertar($datos)
    {
        // Insertar el registro principal
        $this->db->query("INSERT INTO sw_curso (cu_nombre, cu_shortname, cu_abreviatura) VALUES (:cu_nombre, :cu_shortname, :cu_abreviatura)");

        //Vincular valores
        $this->db->bind(':cu_nombre', $datos['cu_nombre']);
        $this->db->bind(':cu_shortname', $datos['cu_shortname']);
        $this->db->bind(':cu_abreviatura', $datos['cu_abreviatura']);

        $this->db->execute();

        // Obtener el id_curso del registro insertado
        $this->db->query("SELECT MAX(id_curso) AS lastInsertId FROM sw_curso");
        $lastInsertId = $this->db->registro()->lastInsertId;

        // Generar los cursos de acuerdo a los subniveles de educación seleccionados
        $subniveles = $datos['subniveles'];

        for ($i = 0; $i < count($subniveles); $i++) {

            //Verificar si tiene especialidades asociadas
            $this->db->query("SELECT * FROM sw_especialidad WHERE subnivel_id = $subniveles[$i]");
            $especialidades_asociadas = $this->db->registros();

            if (count($especialidades_asociadas) > 0) {
                foreach ($especialidades_asociadas as $especialidad) {

                    $this->db->query("SELECT MAX(orden) AS max_orden FROM sw_curso_subnivel");
                    $registro = $this->db->registro();
                    $max_orden = empty($registro->max_orden) ? 1 : $registro->max_orden + 1;

                    $this->db->query('INSERT INTO sw_curso_subnivel (especialidad_id, curso_id, subnivel_id, orden) VALUES (:especialidad_id, :curso_id, :subnivel_id, :orden)');

                    //Vincular valores
                    $this->db->bind(':especialidad_id', $especialidad->id_especialidad);
                    $this->db->bind(':curso_id', $lastInsertId);
                    $this->db->bind(':subnivel_id', $especialidad->subnivel_id);
                    $this->db->bind(':orden', $max_orden);

                    $this->db->execute();
                }
            }
        }
    }

    public function actualizar($datos)
    {
        $id_curso = $datos['id_curso'];

        // Actualizar el registro principal
        $this->db->query("UPDATE sw_curso SET cu_nombre = :cu_nombre, cu_shortname = :cu_shortname, cu_abreviatura = :cu_abreviatura WHERE id_curso = :id_curso");

        //Vincular valores
        $this->db->bind(':id_curso', $id_curso);
        $this->db->bind(':cu_nombre', $datos['cu_nombre']);
        $this->db->bind(':cu_shortname', $datos['cu_shortname']);
        $this->db->bind(':cu_abreviatura', $datos['cu_abreviatura']);

        $this->db->execute();

        // Actualizar los subniveles asociados
        $this->db->query("DELETE FROM sw_curso_subnivel WHERE curso_id = $id_curso");
        $this->db->execute();

        $subniveles = $datos['subniveles'];

        for ($i = 0; $i < count($datos['subniveles']); $i++) {
            //Verificar si tiene especialidades asociadas
            $this->db->query("SELECT * FROM sw_especialidad WHERE subnivel_id = $subniveles[$i]");
            $especialidades_asociadas = $this->db->registros();

            if (count($especialidades_asociadas) > 0) {
                foreach ($especialidades_asociadas as $especialidad) {

                    $this->db->query("SELECT MAX(orden) AS max_orden FROM sw_curso_subnivel");
                    $registro = $this->db->registro();
                    $max_orden = empty($registro->max_orden) ? 1 : $registro->max_orden + 1;

                    $this->db->query('INSERT INTO sw_curso_subnivel (especialidad_id, curso_id, subnivel_id, orden) VALUES (:especialidad_id, :curso_id, :subnivel_id, :orden)');

                    //Vincular valores
                    $this->db->bind(':especialidad_id', $especialidad->id_especialidad);
                    $this->db->bind(':curso_id', $id_curso);
                    $this->db->bind(':subnivel_id', $especialidad->subnivel_id);
                    $this->db->bind(':orden', $max_orden);

                    $this->db->execute();
                }
            }
        }
    }
}
