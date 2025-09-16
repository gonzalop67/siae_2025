<?php
class Comentario
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function contarComentarios()
    {
        $this->db->query("SELECT COUNT(*) AS nro_comentarios
                            FROM sw_comentario");
        return $this->db->registro()->nro_comentarios;
    }
}