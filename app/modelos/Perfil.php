<?php
class Perfil
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function obtenerPerfiles()
    {
        $this->db->query("SELECT * FROM sw_perfil ORDER BY pe_nombre ASC");
        return $this->db->registros();
    }

    public function obtenerPerfil($id)
    {
        $this->db->query("SELECT * FROM sw_perfil WHERE id_perfil = $id");
        return $this->db->registro();
    }

    public function existeNombrePerfil($pe_nombre)
    {
        $this->db->query("SELECT id_perfil FROM sw_perfil WHERE pe_nombre = '$pe_nombre'");
        $perfil = $this->db->registro();

        return !empty($perfil);
    }

    public function existeSlugPerfil($pe_slug)
    {
        $this->db->query("SELECT id_perfil FROM sw_perfil WHERE pe_slug = '$pe_slug'");
        $perfil = $this->db->registro();

        return !empty($perfil);
    }

    public function insertarPerfil($datos)
    {
        $this->db->query('INSERT INTO sw_perfil (pe_nombre, pe_slug) VALUES (:pe_nombre, :pe_slug)');

        //Vincular valores
        $this->db->bind(':pe_nombre', $datos['pe_nombre']);
        $this->db->bind(':pe_slug', $datos['pe_slug']);

        $this->db->execute();
    }

    public function actualizarPerfil($datos)
    {
        $this->db->query('UPDATE sw_perfil SET pe_nombre = :pe_nombre, pe_slug = :pe_slug WHERE id_perfil = :id_perfil');

        //Vincular valores
        $this->db->bind(':id_perfil', $datos['id_perfil']);
        $this->db->bind(':pe_nombre', $datos['pe_nombre']);
        $this->db->bind(':pe_slug', $datos['pe_slug']);

        $this->db->execute();
    }

    public function eliminarPerfil($id)
    {
        $this->db->query('DELETE FROM `sw_perfil` WHERE `id_perfil` = :id_perfil');

        //Vincular valores
        $this->db->bind(':id_perfil', $id);

        return $this->db->execute();
    }
}
