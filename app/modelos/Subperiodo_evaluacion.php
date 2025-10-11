<?php
class Subperiodo_evaluacion
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function obtenerTodos()
    {
        $this->db->query("SELECT * FROM sw_sub_periodo_evaluacion ORDER BY pe_orden ASC");
        return $this->db->registros();
    }

    public function obtener($id)
    {
        $this->db->query("SELECT * FROM sw_sub_periodo_evaluacion WHERE id_sub_periodo_evaluacion = $id");
        return $this->db->registro();
    }

    public function existeCampo($columna, $valor)
    {
        return $this->db->existValueColumn('sw_sub_periodo_evaluacion', $columna, $valor);
    }

    public function insertar($datos)
    {
        $this->db->query('SELECT MAX(pe_orden) AS max_orden FROM sw_sub_periodo_evaluacion');
        $registro = $this->db->registro();
        $max_orden = (!empty($registro)) ? $registro->max_orden + 1 : 1;

        $this->db->query('INSERT INTO sw_sub_periodo_evaluacion (id_tipo_periodo, pe_nombre, pe_abreviatura, pe_orden) VALUES (:id_tipo_periodo, :pe_nombre, :pe_abreviatura, :orden)');

        //Vincular valores
        $this->db->bind(':id_tipo_periodo', $datos['id_tipo_periodo']);
        $this->db->bind(':pe_nombre', $datos['pe_nombre']);
        $this->db->bind(':pe_abreviatura', $datos['pe_abreviatura']);
        $this->db->bind(':orden', $max_orden);

        $this->db->execute();
    }

    public function actualizar($datos)
    {
        $this->db->query('UPDATE sw_sub_periodo_evaluacion SET id_tipo_periodo = :id_tipo_periodo, pe_nombre = :pe_nombre, pe_abreviatura = :pe_abreviatura WHERE id_sub_periodo_evaluacion = :id_sub_periodo_evaluacion');

        //Vincular valores
        $this->db->bind(':id_tipo_periodo', $datos['id_tipo_periodo']);
        $this->db->bind(':pe_nombre', $datos['pe_nombre']);
        $this->db->bind(':pe_abreviatura', $datos['pe_abreviatura']);
        $this->db->bind(':id_sub_periodo_evaluacion', $datos['id_sub_periodo_evaluacion']);

        $this->db->execute();
    }

    public function eliminar($id)
    {
        $this->db->query('DELETE FROM `sw_sub_periodo_evaluacion` WHERE `id_sub_periodo_evaluacion` = :id_sub_periodo_evaluacion');

        //Vincular valores
        $this->db->bind(':id_sub_periodo_evaluacion', $id);

        return $this->db->execute();
    }
}