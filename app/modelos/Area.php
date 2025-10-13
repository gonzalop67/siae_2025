<?php
class Area
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function obtener($id_area)
    {
        $this->db->query("SELECT * FROM sw_area WHERE id_area = $id_area");
        return $this->db->registro();
    }

    public function obtenerAreas()
    {
        $this->db->query("SELECT * FROM sw_area ORDER BY ar_nombre");
        return $this->db->registros();
    }

    public function existeNombre($ar_nombre)
    {
        $this->db->query("SELECT id_area FROM sw_area WHERE ar_nombre = '$ar_nombre'");
        $this->db->registro();

        return $this->db->rowCount() > 0;
    }

    public function insertar($datos)
    {
        $this->db->query('INSERT INTO sw_area (ar_nombre, ar_activo) VALUES (:ar_nombre, :ar_activo)');

        //Vincular valores
        $this->db->bind(':ar_nombre', $datos['ar_nombre']);
        $this->db->bind(':ar_activo', $datos['ar_activo']);

        return $this->db->execute();
    }

    public function actualizar($datos)
    {
        $this->db->query('UPDATE sw_area SET ar_nombre = :ar_nombre, ar_activo = :ar_activo WHERE id_area = :id_area');

        //Vincular valores
        $this->db->bind(':id_area', $datos['id_area']);
        $this->db->bind(':ar_nombre', $datos['ar_nombre']);
        $this->db->bind(':ar_activo', $datos['ar_activo']);

        return $this->db->execute();
    }

    public function eliminar($id_area)
    {
        $this->db->query('DELETE FROM sw_area WHERE id_area = :id_area');

        //Vincular valores
        $this->db->bind(':id_area', $id_area);

        return $this->db->execute();
    }
}