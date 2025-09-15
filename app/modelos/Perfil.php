<?php
class Perfil
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function obtenerPerfiles()
    {
        $this->db->query("SELECT * FROM sw_perfil ORDER BY pe_nombre ASC");
        return $this->db->registros();
    }
}
