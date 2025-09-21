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

    public function obtenerUsuarios()
    {
        $query = "SELECT * FROM sw_usuario ORDER BY us_apellidos, us_nombres";
        $this->db->query($query);
        return $this->db->registros();
    }

    public function obtenerUsuarioPorId($id)
    {
        $this->db->query("SELECT * FROM sw_usuario WHERE id_usuario = $id");
        return $this->db->registro();
    }

    public function existeUsuarioPorNombreUsuario($us_login)
    {
        $this->db->query("SELECT id_usuario FROM sw_usuario WHERE us_login = '$us_login'");
        $usuario = $this->db->registro();

        return !empty($usuario);
    }

    public function existeUsuarioPorNombreCompleto($us_fullname)
    {
        $this->db->query("SELECT id_usuario FROM sw_usuario WHERE us_fullname = '$us_fullname'");
        $usuario = $this->db->registro();

        return !empty($usuario);
    }

    public function insertarUsuario($datos)
    {
        $this->db->query('INSERT INTO sw_usuario (us_titulo, us_titulo_descripcion, us_apellidos, us_nombres, us_shortname, us_fullname, us_login, us_password, us_foto, us_genero, us_activo) VALUES (:us_titulo, :us_titulo_descripcion, :us_apellidos, :us_nombres, :us_shortname, :us_fullname, :us_login, :us_password, :us_foto, :us_genero, :us_activo)');

        //Vincular valores
        $this->db->bind(':us_titulo', $datos['us_titulo']);
        $this->db->bind(':us_titulo_descripcion', $datos['us_titulo_descripcion']);
        $this->db->bind(':us_apellidos', $datos['us_apellidos']);
        $this->db->bind(':us_nombres', $datos['us_nombres']);
        $this->db->bind(':us_shortname', $datos['us_shortname']);
        $this->db->bind(':us_fullname', $datos['us_fullname']);
        $this->db->bind(':us_login', $datos['us_login']);
        $this->db->bind(':us_password', $datos['us_password']);
        $this->db->bind(':us_foto', $datos['us_foto']);
        $this->db->bind(':us_genero', $datos['us_genero']);
        $this->db->bind(':us_activo', $datos['us_activo']);

        $this->db->execute();

        $this->db->query("SELECT MAX(id_usuario) AS lastInsertId FROM sw_usuario");
        $lastInsertId = $this->db->registro()->lastInsertId;

        for ($i = 0; $i < count($datos['perfiles']); $i++) {
            //Insertar en la tabla sw_usuario_perfil
            $this->db->query("INSERT INTO sw_usuario_perfil (id_usuario, id_perfil) VALUES (:id_usuario, :id_perfil)");

            //Vincular valores
            $this->db->bind(':id_usuario', $lastInsertId);
            $this->db->bind(':id_perfil', $datos['perfiles'][$i]);

            $this->db->execute();
        }
    }

    public function actualizarUsuario($datos)
    {
        $this->db->query('UPDATE sw_usuario SET us_titulo = :us_titulo, us_titulo_descripcion = :us_titulo_descripcion, us_apellidos = :us_apellidos, us_nombres = :us_nombres, us_shortname = :us_shortname, us_fullname = :us_fullname, us_login = :us_login, us_password = :us_password, us_foto = :us_foto, us_genero = :us_genero, us_activo = :us_activo WHERE id_usuario = :id_usuario');

        //Vincular valores
        $id_usuario = $datos['id_usuario'];
        $this->db->bind(':id_usuario', $datos['id_usuario']);
        $this->db->bind(':us_titulo', $datos['us_titulo']);
        $this->db->bind(':us_titulo_descripcion', $datos['us_titulo_descripcion']);
        $this->db->bind(':us_apellidos', $datos['us_apellidos']);
        $this->db->bind(':us_nombres', $datos['us_nombres']);
        $this->db->bind(':us_shortname', $datos['us_shortname']);
        $this->db->bind(':us_fullname', $datos['us_fullname']);
        $this->db->bind(':us_login', $datos['us_login']);
        $this->db->bind(':us_password', $datos['us_password']);
        $this->db->bind(':us_foto', $datos['us_foto']);
        $this->db->bind(':us_genero', $datos['us_genero']);
        $this->db->bind(':us_activo', $datos['us_activo']);

        $this->db->execute();

        $this->db->query("DELETE FROM sw_usuario_perfil WHERE id_usuario = $id_usuario");
        $this->db->execute();

        for ($i = 0; $i < count($datos['perfiles']); $i++) {
            //Insertar en la tabla sw_usuario_perfil
            $this->db->query("INSERT INTO sw_usuario_perfil (id_usuario, id_perfil) VALUES (:id_usuario, :id_perfil)");

            //Vincular valores
            $this->db->bind(':id_usuario', $id_usuario);
            $this->db->bind(':id_perfil', $datos['perfiles'][$i]);

            $this->db->execute();
        }
    }

    public function eliminarUsuario($id)
    {
        $this->db->query('DELETE FROM `sw_usuario` WHERE `id_usuario` = :id_usuario');

        //Vincular valores
        $this->db->bind(':id_usuario', $id);

        return $this->db->execute();
    }

    public function obtenerPerfiles($id_usuario)
    {
        $perfiles = $this->db->query("SELECT u.id_usuario, 
                                             p.pe_nombre 
                                        FROM sw_usuario u, 
                                             sw_perfil p, 
                                             sw_usuario_perfil up 
                                       WHERE u.id_usuario = up.id_usuario 
                                         AND p.id_perfil = up.id_perfil 
                                         AND u.id_usuario = $id_usuario 
                                       ORDER BY p.pe_nombre ASC");

        return $this->db->registros();
    }

    public function obtenerPerfilesUsuario($id_usuario)
    {
        $this->db->query("SELECT * FROM sw_usuario_perfil WHERE id_usuario = $id_usuario");
        $registros = $this->db->registros();
        $array = array();
        foreach ($registros as $r) {
            array_push($array, $r->id_perfil);
        }
        return $array;
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
