<?php

namespace App\Models;

class UsuarioPerfil extends Model
{
    protected string $table = 'sw_usuario_perfil';
    protected array $fillable = ['id_usuario', 'id_perfil'];

}
