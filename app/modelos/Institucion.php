<?php
class Institucion
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function obtenerInstituciones()
    {
        $this->db->query("SELECT 
                            i.id_institucion, 
                            in_logo, 
                            us_shortname, 
                            in_nombre, 
                            in_direccion 
                        FROM sw_institucion i, 
                            sw_usuario u 
                        WHERE u.id_usuario = i.admin_id 
                        ORDER BY in_nombre");
        return $this->db->registros();
    }

    public function obtenerInstitucion($id)
    {
        $this->db->query("SELECT * FROM sw_institucion where id_institucion = $id");
        return $this->db->registro();
    }

    public function obtenerAdministrador($id)
    {
        $this->db->query("SELECT id_usuario, us_foto FROM sw_usuario u, sw_institucion i WHERE u.id_usuario = i.admin_id AND i.id_institucion = $id");
        return $this->db->registro();
    }

    public function obtenerPosiblesAdministradores()
    {
        $this->db->query("SELECT u.id_usuario, 
                                 us_shortname 
                            FROM sw_usuario u,
                                 sw_usuario_perfil up,
                                 sw_perfil p  
                           WHERE u.id_usuario = up.id_usuario 
                             AND p.id_perfil = up.id_perfil
                             AND pe_slug = 'administrador-de-ue' 
                           ORDER BY us_apellidos, us_nombres");
        return $this->db->registros();
    }

    public function obtenerNombreInstitucion()
    {
        $this->db->query("SELECT in_nombre FROM sw_institucion WHERE id_institucion = 1");
        return $this->db->registro()->in_nombre;
    }

    public function existeNombreInstitucion($in_nombre)
    {
        $this->db->query("SELECT id_institucion FROM sw_institucion WHERE in_nombre = '$in_nombre'");
        $institucion = $this->db->registro();

        return !empty($institucion);
    }

    public function existeEmailInstitucion($in_email)
    {
        $this->db->query("SELECT id_institucion FROM sw_institucion WHERE in_email = '$in_email'");
        $institucion = $this->db->registro();

        return !empty($institucion);
    }

    public function existeURLInstitucion($in_url)
    {
        $this->db->query("SELECT id_institucion FROM sw_institucion WHERE in_url = '$in_url'");
        $institucion = $this->db->registro();

        return !empty($institucion);
    }

    public function existeAMIEInstitucion($in_amie)
    {
        $this->db->query("SELECT id_institucion FROM sw_institucion WHERE in_amie = '$in_amie'");
        $institucion = $this->db->registro();

        return !empty($institucion);
    }

    public function existeCampo($col_name, $in_nombre)
    {
        return $this->db->existValueColumn('sw_institucion', $col_name, $in_nombre);
    }

    public function insertarInstitucion($datos)
    {
        $this->db->query('INSERT INTO sw_institucion 
                        SET admin_id = :admin_id, 
                        in_nombre = :in_nombre, 
                        in_direccion = :in_direccion, 
                        in_telefono = :in_telefono, 
                        in_regimen = :in_regimen, 
                        in_nom_rector = :in_nom_rector, 
                        in_genero_rector = :in_genero_rector, 
                        in_nom_vicerrector = :in_nom_vicerrector, 
                        in_genero_vicerrector = :in_genero_vicerrector, 
                        in_nom_secretario = :in_nom_secretario, 
                        in_genero_secretario = :in_genero_secretario, 
                        in_email = :in_email, 
                        in_url = :in_url, 
                        in_logo = :in_logo, 
                        in_amie = :in_amie, 
                        in_ciudad = :in_ciudad, 
                        in_copiar_y_pegar = :in_copiar_y_pegar');

        //Vincular valores
        $this->db->bind(':admin_id', $datos['admin_id']);
        $this->db->bind(':in_nombre', $datos['in_nombre']);
        $this->db->bind(':in_direccion', $datos['in_direccion']);
        $this->db->bind(':in_telefono', $datos['in_telefono']);
        $this->db->bind(':in_regimen', $datos['in_regimen']);
        $this->db->bind(':in_nom_rector', $datos['in_nom_rector']);
        $this->db->bind(':in_genero_rector', $datos['in_genero_rector']);
        $this->db->bind(':in_nom_vicerrector', $datos['in_nom_vicerrector']);
        $this->db->bind(':in_genero_vicerrector', $datos['in_genero_vicerrector']);
        $this->db->bind(':in_nom_secretario', $datos['in_nom_secretario']);
        $this->db->bind(':in_genero_secretario', $datos['in_genero_secretario']);
        $this->db->bind(':in_email', $datos['in_email']);
        $this->db->bind(':in_url', $datos['in_url']);
        $this->db->bind(':in_logo', $datos['in_logo']);
        $this->db->bind(':in_amie', $datos['in_amie']);
        $this->db->bind(':in_ciudad', $datos['in_ciudad']);
        $this->db->bind(':in_copiar_y_pegar', $datos['in_copiar_y_pegar']);

        $this->db->execute();
    }

    public function actualizarInstitucion($datos)
    {
        $this->db->query('UPDATE sw_institucion 
                        SET admin_id = :admin_id, 
                        in_nombre = :in_nombre, 
                        in_direccion = :in_direccion, 
                        in_telefono = :in_telefono, 
                        in_regimen = :in_regimen, 
                        in_nom_rector = :in_nom_rector, 
                        in_genero_rector = :in_genero_rector, 
                        in_nom_vicerrector = :in_nom_vicerrector, 
                        in_genero_vicerrector = :in_genero_vicerrector, 
                        in_nom_secretario = :in_nom_secretario, 
                        in_genero_secretario = :in_genero_secretario, 
                        in_email = :in_email, 
                        in_url = :in_url, 
                        in_logo = :in_logo, 
                        in_amie = :in_amie, 
                        in_ciudad = :in_ciudad, 
                        in_copiar_y_pegar = :in_copiar_y_pegar 
                        WHERE id_institucion = :id_institucion');

        //Vincular valores
        $this->db->bind(':id_institucion', $datos['id_institucion']);
        $this->db->bind(':admin_id', $datos['admin_id']);
        $this->db->bind(':in_nombre', $datos['in_nombre']);
        $this->db->bind(':in_direccion', $datos['in_direccion']);
        $this->db->bind(':in_telefono', $datos['in_telefono']);
        $this->db->bind(':in_regimen', $datos['in_regimen']);
        $this->db->bind(':in_nom_rector', $datos['in_nom_rector']);
        $this->db->bind(':in_genero_rector', $datos['in_genero_rector']);
        $this->db->bind(':in_nom_vicerrector', $datos['in_nom_vicerrector']);
        $this->db->bind(':in_genero_vicerrector', $datos['in_genero_vicerrector']);
        $this->db->bind(':in_nom_secretario', $datos['in_nom_secretario']);
        $this->db->bind(':in_genero_secretario', $datos['in_genero_secretario']);
        $this->db->bind(':in_email', $datos['in_email']);
        $this->db->bind(':in_url', $datos['in_url']);
        $this->db->bind(':in_logo', $datos['in_logo']);
        $this->db->bind(':in_amie', $datos['in_amie']);
        $this->db->bind(':in_ciudad', $datos['in_ciudad']);
        $this->db->bind(':in_copiar_y_pegar', $datos['in_copiar_y_pegar']);

        $this->db->execute();
    }

    public function eliminarInstitucion($id)
    {
        $this->db->query('DELETE FROM `sw_institucion` WHERE `id_institucion` = :id_institucion');

        //Vincular valores
        $this->db->bind(':id_institucion', $id);

        return $this->db->execute();
    }
}
