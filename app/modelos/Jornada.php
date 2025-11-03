<?php
class Jornada
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function obtenerJornadas()
    {
        $this->db->query("SELECT * FROM sw_jornada ORDER BY jo_orden ASC");
        return $this->db->registros();
    }

    public function obtener($id)
    {
        $this->db->query("SELECT * FROM sw_jornada WHERE id_jornada = $id");
        return $this->db->registro();
    }

    public function existeNombre($nombre)
    {
        $this->db->query("SELECT * FROM sw_jornada WHERE jo_nombre = '$nombre'");
        $this->db->registro();

        return $this->db->rowCount() > 0;
    }

    public function insertar($datos)
    {
        $this->db->query("SELECT MAX(jo_orden) AS max_orden FROM sw_jornada");
        $registro = $this->db->registro();
        $max_orden = empty($registro) ? 1 : $registro->max_orden + 1;

        $this->db->query('INSERT INTO sw_jornada (jo_nombre, jo_orden) VALUES (:jo_nombre, :jo_orden)');

        //Vincular valores
        $this->db->bind(':jo_nombre', $datos['jo_nombre']);
        $this->db->bind(':jo_orden', $max_orden);

        return $this->db->execute();
    }

    public function actualizar($datos)
    {
        $this->db->query("UPDATE sw_jornada SET jo_nombre = :jo_nombre WHERE id_jornada = :id_jornada");

        //Vincular valores
        $this->db->bind(':id_jornada', $datos['id_jornada']);
        $this->db->bind(':jo_nombre', $datos['jo_nombre']);

        return $this->db->execute();
    }

    public function eliminar($id)
    {
        $this->db->query('DELETE FROM `sw_jornada` WHERE `id_jornada` = :id_jornada');

        //Vincular valores
        $this->db->bind(':id_jornada', $id);

        return $this->db->execute();
    }

    public function actualizarOrden($id_jornada, $jo_orden)
    {
        $this->db->query('UPDATE `sw_jornada` SET `jo_orden` = :jo_orden WHERE `id_jornada` = :id_jornada');

        //Vincular valores
        $this->db->bind(':id_jornada', $id_jornada);
        $this->db->bind(':jo_orden', $jo_orden);

        echo $this->db->execute();
    }
}