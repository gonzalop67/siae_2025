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

    public function obtenerSubnivel($id)
    {
        $this->db->query("SELECT * FROM sw_nivel_educacion WHERE id_nivel_educacion = $id");
        return $this->db->registro();
    }

    public function existeNombre($nombre)
    {
        $this->db->query("SELECT * FROM sw_nivel_educacion WHERE nombre = '$nombre'");
        $subnivel = $this->db->registro();

        return !empty($subnivel);
    }

    public function insertar($datos)
    {
        $this->db->query('SELECT MAX(orden) AS max_orden FROM sw_nivel_educacion');
        $registro = $this->db->registro();
        $max_orden = (!empty($registro)) ? $registro->max_orden + 1 : 1;

        $this->db->query('INSERT INTO sw_nivel_educacion (nombre, es_bachillerato, orden) VALUES (:nombre, :es_bachillerato, :orden)');

        //Vincular valores
        $this->db->bind(':nombre', $datos['nombre']);
        $this->db->bind(':es_bachillerato', $datos['es_bachillerato']);
        $this->db->bind(':orden', $max_orden);

        $this->db->execute();
    }

    public function actualizar($datos)
    {
        $this->db->query('UPDATE sw_nivel_educacion SET nombre = :nombre, es_bachillerato = :es_bachillerato WHERE id_nivel_educacion = :id_nivel_educacion');

        //Vincular valores
        $this->db->bind(':nombre', $datos['nombre']);
        $this->db->bind(':es_bachillerato', $datos['es_bachillerato']);
        $this->db->bind(':id_nivel_educacion', $datos['id_nivel_educacion']);

        $this->db->execute();
    }

    public function eliminar($id)
    {
        $this->db->query('DELETE FROM `sw_nivel_educacion` WHERE `id_nivel_educacion` = :id_nivel_educacion');

        //Vincular valores
        $this->db->bind(':id_nivel_educacion', $id);

        return $this->db->execute();
    }
}