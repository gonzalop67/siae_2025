<?php
class Usuario
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function obtenerUsuario($login, $clave, $id_perfil)
    {
        $query = "SELECT u.id_usuario, us_foto, us_shortname, pe_nombre "
            . " FROM sw_usuario u, "
            . "      sw_perfil p, "
            . "      sw_usuario_perfil up "
            . "WHERE u.id_usuario = up.id_usuario "
            . "  AND p.id_perfil = up.id_perfil "
            . "  AND us_login = '$login' "
            . "  AND us_password = '$clave' "
            . "  AND p.id_perfil = $id_perfil"
            . "  AND us_activo = 1";

        $this->db->query($query);
        return $this->db->registro();
    }

    public function contarAutoridades()
    {
        $this->db->query("SELECT COUNT(*) AS nro_autoridades 
                            FROM sw_usuario u,
                                 sw_perfil p, 
                                 sw_usuario_perfil up 
                           WHERE u.id_usuario = up.id_usuario 
                             AND p.id_perfil = up.id_perfil 
                             AND pe_nombre = 'AUTORIDAD' 
                             AND us_activo = 1");
        return $this->db->registro()->nro_autoridades;
    }

    public function contarDocentes()
    {
        $this->db->query("SELECT COUNT(*) AS nro_docentes 
                            FROM sw_usuario u,
                                 sw_perfil p, 
                                 sw_usuario_perfil up 
                           WHERE u.id_usuario = up.id_usuario 
                             AND p.id_perfil = up.id_perfil 
                             AND pe_nombre = 'DOCENTE' 
                             AND us_activo = 1");
        return $this->db->registro()->nro_docentes;
    }

    public function contarRepresentantes($id_periodo_lectivo)
    {
        $this->db->query("SELECT COUNT(*) AS nro_representantes
                            FROM sw_representante r,
                                 sw_estudiante_periodo_lectivo ep
                           WHERE r.id_estudiante = ep.id_estudiante
                             AND id_periodo_lectivo = $id_periodo_lectivo");
        return $this->db->registro()->nro_representantes;
    }
}