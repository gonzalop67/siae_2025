<?php
class Institucion
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    function obtenerDatosInstitucion()
    {
        $this->db->query("SELECT * FROM sw_institucion where id_institucion = 1");
        return $this->db->registro();
    }

    public function obtenerNombreInstitucion()
    {
        $this->db->query("SELECT in_nombre FROM sw_institucion WHERE id_institucion = 1");
        return $this->db->registro()->in_nombre;
    }
}
