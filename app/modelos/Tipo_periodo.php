<?php
class Tipo_periodo
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function obtenerTodos()
    {
        $this->db->query("SELECT * FROM sw_tipo_periodo ORDER BY id_tipo_periodo ASC");
        return $this->db->registros();
    }

    public function obtener($id)
    {
        $this->db->query("SELECT * FROM sw_tipo_periodo WHERE id_tipo_periodo = $id");
        return $this->db->registro();
    }

    public function existeCampo($columna, $valor)
    {
        return $this->db->existValueColumn('sw_tipo_periodo', $columna, $valor);
    }

    public function insertar($datos)
    {
        $this->db->query('INSERT INTO sw_tipo_periodo (tp_descripcion, tp_slug) VALUES (:tp_descripcion, :tp_slug)');

        //Vincular valores
        $this->db->bind(':tp_descripcion', $datos['tp_descripcion']);
        $this->db->bind(':tp_slug', $datos['tp_slug']);

        $this->db->execute();
    }

    public function actualizar($datos)
    {
        $this->db->query('UPDATE sw_tipo_periodo SET tp_descripcion = :tp_descripcion, tp_slug = :tp_slug WHERE id_tipo_periodo = :id_tipo_periodo');

        //Vincular valores
        $this->db->bind(':id_tipo_periodo', $datos['id_tipo_periodo']);
        $this->db->bind(':tp_descripcion', $datos['tp_descripcion']);
        $this->db->bind(':tp_slug', $datos['tp_slug']);

        $this->db->execute();
    }

    public function eliminar($id)
    {
        $this->db->query('DELETE FROM `sw_tipo_periodo` WHERE `id_tipo_periodo` = :id_tipo_periodo');

        //Vincular valores
        $this->db->bind(':id_tipo_periodo', $id);

        return $this->db->execute();
    }
}
