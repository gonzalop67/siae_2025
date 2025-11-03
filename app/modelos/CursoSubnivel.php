<?php
class CursoSubnivel
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function obtenerCursosSubnivel()
    {
        $this->db->query("SELECT id_curso_subnivel, 
                                 sn.nombre, 
                                 es_figura, 
                                 cu_nombre
                            FROM sw_curso_subnivel cs, 
                                 sw_sub_nivel_educacion sn, 
                                 sw_especialidad e, 
                                 sw_curso c 
                           WHERE sn.id = cs.subnivel_id 
                             AND e.id_especialidad = cs.especialidad_id 
                             AND c.id_curso = cs.curso_id 
                           ORDER BY cs.orden");
        return $this->db->registros();
    }

}