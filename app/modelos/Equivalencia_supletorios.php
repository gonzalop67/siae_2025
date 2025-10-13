<?php
class Equivalencia_supletorios
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function obtenerTodos()
    {
        $this->db->query("SELECT * FROM sw_equivalencia_supletorios ORDER BY pe_orden ASC");
        return $this->db->registros();
    }
}