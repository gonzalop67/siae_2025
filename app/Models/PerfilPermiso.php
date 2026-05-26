<?php

namespace App\Models;

class PerfilPermiso extends Model
{
    protected string $table = 'sw_perfil_permiso';
    protected array $fillable = ['id_perfil', 'id_permiso'];

    public function sync(int $id, array $permissionIds)
    {
        $sql = "DELETE FROM {$this->table} WHERE id_perfil = ?";
        $this->query($sql, [$id], 'i');

        for ($i = 0; $i < count($permissionIds); $i++) {
            //Insertar en la tabla sw_perfil_permiso
            $sql = "INSERT INTO {$this->table} (id_perfil, id_permiso) VALUES (?, ?)";
            // var_dump($sql); die();
            $this->query($sql, [$id, $permissionIds[$i]], 'ii');
        }
    }
}