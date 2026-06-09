<?php

namespace App\Models;

class Institucion extends Model
{
    protected string $table = 'sw_institucion';
    protected string $primaryKey = 'id_institucion';
    protected array $fillable = [
        'admin_id',
        'in_nombre',
        'in_direccion',
        'in_telefono',
        'in_regimen',
        'in_nom_rector',
        'in_genero_rector',
        'in_nom_vicerrector',
        'in_genero_vicerrector',
        'in_nom_secretario',
        'in_genero_secretario',
        'in_email',
        'in_url',
        'in_logo',
        'in_amie',
        'in_ciudad',
        'in_copiar_y_pegar'
    ];

    public function obtenerPosiblesAdministradores()
    {
        $sql = "SELECT u.id_usuario, 
                       us_shortname, 
                       us_foto
                  FROM sw_usuario u,
                       sw_usuario_perfil up,
                       sw_perfil p  
                 WHERE u.id_usuario = up.id_usuario 
                   AND p.id_perfil = up.id_perfil
                   AND pe_slug = 'administrador-de-ue' 
                 ORDER BY us_apellidos, us_nombres";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $rows;
    }
}
