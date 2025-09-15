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
}