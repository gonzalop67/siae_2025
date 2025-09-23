<?php
class Institucion
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function obtenerInstituciones()
    {
        $this->db->query("SELECT 
                            id_institucion, 
                            in_logo, 
                            us_shortname, 
                            in_email, 
                            in_direccion 
                        FROM sw_institucion i, 
                            sw_usuario u 
                        WHERE u.id_usuario = i.admin_id 
                        ORDER BY in_nombre");
        return $this->db->registros();
    }

    public function obtenerDatosInstitucion()
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
