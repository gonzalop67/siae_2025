<?php
class Aporte_evaluacion
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function obtener($id_aporte_evaluacion)
    {
        $this->db->query("SELECT * FROM sw_aporte_evaluacion WHERE id_aporte_evaluacion = $id_aporte_evaluacion");
        return $this->db->registro();
    }

    public function obtenerAportesEvaluacion()
    {
        $this->db->query("SELECT * FROM sw_aporte_evaluacion ORDER BY id_aporte_evaluacion");
        return $this->db->registros();
    }

    public function existeCampo($campo, $valor)
    {
        $this->db->query("SELECT id_aporte_evaluacion FROM sw_aporte_evaluacion WHERE $campo = '$valor'");
        $this->db->registro();

        return $this->db->rowCount() > 0;
    }

    public function insertar($datos)
    {
        $this->db->query("INSERT INTO sw_aporte_evaluacion (id_tipo_aporte, ap_nombre, ap_abreviatura, ap_descripcion) VALUES (:id_tipo_aporte, :ap_nombre, :ap_abreviatura, :ap_descripcion)");
        $this->db->bind(':id_tipo_aporte', $datos['id_tipo_aporte']);
        $this->db->bind(':ap_nombre', $datos['ap_nombre']);
        $this->db->bind(':ap_abreviatura', $datos['ap_abreviatura']);
        $this->db->bind(':ap_descripcion', $datos['ap_descripcion']);
        return $this->db->execute();
    }

    public function actualizar($datos)
    {
        $this->db->query('UPDATE sw_aporte_evaluacion SET ap_nombre = :ap_nombre, ap_abreviatura = :ap_abreviatura, ap_descripcion = :ap_descripcion WHERE id_aporte_evaluacion = :id_aporte_evaluacion');

        //Vincular valores
        $this->db->bind(':id_aporte_evaluacion', $datos['id_aporte_evaluacion']);
        $this->db->bind(':ap_nombre', $datos['ap_nombre']);
        $this->db->bind(':ap_abreviatura', $datos['ap_abreviatura']);
        $this->db->bind(':ap_descripcion', $datos['ap_descripcion']);

        return $this->db->execute();
    }

}