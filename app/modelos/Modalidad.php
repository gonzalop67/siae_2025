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
        $this->db->query("SELECT * FROM sw_modalidad ORDER BY mo_orden ASC");
        return $this->db->registros();
    }

    public function obtenerModalidad($id_modalidad)
    {
        $this->db->query("SELECT * FROM sw_modalidad WHERE id_modalidad = $id_modalidad");
        return $this->db->registro();
    }

    public function existeNombre($mo_nombre)
    {
        return $this->db->existValueColumn('sw_modalidad', 'mo_nombre', $mo_nombre);
    }

    public function insertar($datos)
    {
        $this->db->query("SELECT MAX(id_modalidad) AS max_id FROM sw_modalidad");
        $registro = $this->db->registro();
        $max_id = empty($registro) ? '1' : $registro->max_id + 1;

        $this->db->query('INSERT INTO sw_modalidad (mo_nombre, mo_activo, mo_orden) VALUES (:mo_nombre, :mo_activo, :mo_orden)');

        //Vincular valores
        $this->db->bind(':mo_nombre', $datos['mo_nombre']);
        $this->db->bind(':mo_activo', $datos['mo_activo']);
        $this->db->bind(':mo_orden', $max_id);

        $this->db->execute();
    }

    public function actualizar($datos)
    {
        $this->db->query('UPDATE sw_modalidad SET mo_nombre = :mo_nombre, mo_activo = :mo_activo WHERE id_modalidad = :id_modalidad');

        //Vincular valores
        $this->db->bind(':id_modalidad', $datos['id_modalidad']);
        $this->db->bind(':mo_nombre', $datos['mo_nombre']);
        $this->db->bind(':mo_activo', $datos['mo_activo']);

        $this->db->execute();
    }

    public function eliminar($id)
    {
        $this->db->query('DELETE FROM `sw_modalidad` WHERE `id_modalidad` = :id_modalidad');

        //Vincular valores
        $this->db->bind(':id_modalidad', $id);

        return $this->db->execute();
    }

    public function actualizarOrden($id_modalidad, $mo_orden)
    {
        $this->db->query('UPDATE `sw_modalidad` SET `mo_orden` = :mo_orden WHERE `id_modalidad` = :id_modalidad');

        //Vincular valores
        $this->db->bind(':id_modalidad', $id_modalidad);
        $this->db->bind(':mo_orden', $mo_orden);

        echo $this->db->execute();
    }
}
