<?php
class Tipo_asignatura
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function obtenerTodos()
    {
        $this->db->query("SELECT * FROM sw_tipo_asignatura ORDER BY ta_descripcion");

        return $this->db->registros();
    }
}