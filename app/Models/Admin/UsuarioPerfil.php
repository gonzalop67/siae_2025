<?php

namespace App\Models\Admin;

use App\Models\Model;

class UsuarioPerfil extends Model
{
    protected string $table = 'sw_usuario_perfil';
    protected array $fillable = ['id_usuario', 'id_perfil'];

    public function sync(int $id, array $rolesIds)
    {
        $sql = "DELETE FROM {$this->table} WHERE id_usuario = ?";
        $this->query($sql, [$id], 'i');

        for ($i = 0; $i < count($rolesIds); $i++) {
            //Insertar en la tabla sw_usuario_perfil
            $sql = "INSERT INTO {$this->table} (id_usuario, id_perfil) VALUES (?, ?)";
            // var_dump($sql); die();
            $this->query($sql, [$id, $rolesIds[$i]], 'ii');
        }
    }
}
