<?php
class Especialidad
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function obtenerEspecialidades()
    {
        $this->db->query("SELECT e.*, c.nombre FROM sw_especialidad e, sw_categoria c WHERE c.id_categoria = e.id_categoria ORDER BY es_orden");
        return $this->db->registros();
    }

    public function obtener($id_especialidad)
    {
        $this->db->query("SELECT * FROM sw_especialidad WHERE id_especialidad = $id_especialidad");
        return $this->db->registro();
    }

    public function existeCampo($columna, $value)
    {
        return $this->db->existValueColumn('sw_especialidad', $columna, $value);
    }

    public function insertar($datos)
    {
        $this->db->query("SELECT MAX(es_orden) AS max_orden FROM sw_especialidad");
        $registro = $this->db->registro();
        $max_orden = empty($registro->max_orden) ? $registro->max_orden + 1 : 1;

        $this->db->query('INSERT INTO sw_especialidad (id_categoria, es_figura, es_abreviatura, es_orden) VALUES (:id_categoria, :es_figura, :es_abreviatura, :es_orden)');

        //Vincular valores
        $this->db->bind(':id_categoria', $datos['id_categoria']);
        $this->db->bind(':es_figura', $datos['es_figura']);
        $this->db->bind(':es_abreviatura', $datos['es_abreviatura']);
        $this->db->bind(':es_orden', $max_orden);

        return $this->db->execute();
    }

    public function actualizar($datos)
    {
        $this->db->query('UPDATE sw_especialidad SET id_categoria = :id_categoria, es_figura = :es_figura, es_abreviatura = :es_abreviatura WHERE id_especialidad = :id_especialidad');

        //Vincular valores
        $this->db->bind(':id_especialidad', $datos['id_especialidad']);
        $this->db->bind(':id_categoria', $datos['id_categoria']);
        $this->db->bind(':es_figura', $datos['es_figura']);
        $this->db->bind(':es_abreviatura', $datos['es_abreviatura']);

        return $this->db->execute();
    }

    public function eliminar($id_especialidad)
    {
        $this->db->query('DELETE FROM sw_especialidad WHERE id_especialidad = :id_especialidad');

        //Vincular valores
        $this->db->bind(':id_especialidad', $id_especialidad);

        return $this->db->execute();
    }
}