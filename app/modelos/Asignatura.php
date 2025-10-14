<?php
class Asignatura
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function obtener($id_asignatura)
    {
        $this->db->query("SELECT * FROM sw_asignatura WHERE id_asignatura = $id_asignatura");
        return $this->db->registro();
    }

    public function obtenerAsignaturas()
    {
        $this->db->query("SELECT a.*, 
                                 ar_nombre, 
                                 ta_descripcion 
                            FROM sw_asignatura a,
                                 sw_area ar, 
                                 sw_tipo_asignatura ta  
                           WHERE ar.id_area = a.id_area 
                             AND ta.id_tipo_asignatura = a.id_tipo_asignatura 
                           ORDER BY ar_nombre, as_nombre");

        return $this->db->registros();
    }

    public function existeCampo($columna, $value)
    {
        return $this->db->existValueColumn('sw_asignatura', $columna, $value);
    }

    public function insertar($datos)
    {
        $this->db->query('INSERT INTO sw_asignatura (as_nombre, as_abreviatura, id_area, id_tipo_asignatura) VALUES (:as_nombre, :as_abreviatura, :id_area, :id_tipo_asignatura)');

        //Vincular valores
        $this->db->bind(':as_nombre', $datos['as_nombre']);
        $this->db->bind(':as_abreviatura', $datos['as_abreviatura']);
        $this->db->bind(':id_area', $datos['id_area']);
        $this->db->bind(':id_tipo_asignatura', $datos['id_tipo_asignatura']);

        return $this->db->execute();
    }

    public function actualizar($datos)
    {
        $this->db->query('UPDATE sw_asignatura SET as_nombre = :as_nombre, as_abreviatura = :as_abreviatura, id_area = :id_area, id_tipo_asignatura = :id_tipo_asignatura WHERE id_asignatura = :id_asignatura');

        //Vincular valores
        $this->db->bind(':id_asignatura', $datos['id_asignatura']);
        $this->db->bind(':id_area', $datos['id_area']);
        $this->db->bind(':id_tipo_asignatura', $datos['id_tipo_asignatura']);
        $this->db->bind(':as_nombre', $datos['as_nombre']);
        $this->db->bind(':as_abreviatura', $datos['as_abreviatura']);

        return $this->db->execute();
    }

    public function eliminar($id_asignatura)
    {
        $this->db->query('DELETE FROM sw_asignatura WHERE id_asignatura = :id_asignatura');

        //Vincular valores
        $this->db->bind(':id_asignatura', $id_asignatura);

        return $this->db->execute();
    }
}