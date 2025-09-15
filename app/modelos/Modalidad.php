<?php
class Modalidad
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function obtenerModalidades()
    {
        $this->db->query("SELECT * FROM sw_modalidad WHERE mo_activo = 1 ORDER BY mo_orden ASC");
        return $this->db->registros();
    }
}
