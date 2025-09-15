<?php
class Jornada
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function obtenerJornadasPorPeriodoLectivo($id_periodo_lectivo)
    {
        $this->db->query("SELECT DISTINCT(id_jornada) AS id_jornada FROM sw_paralelo WHERE id_periodo_lectivo = $id_periodo_lectivo ORDER BY id_jornada ASC");
        return $this->db->registros();
    }
}