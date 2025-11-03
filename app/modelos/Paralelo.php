<?php
class Paralelo
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function obtenerParalelos($id_periodo_lectivo)
    {
        $this->db->query("SELECT * FROM sw_paralelo WHERE id_periodo_lectivo = $id_periodo_lectivo ORDER BY pa_orden ASC");
        return $this->db->registros();
    }

}