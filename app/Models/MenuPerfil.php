<?php

namespace App\Models;

use App\Models\Model;

class MenuPerfil extends Model
{
    protected string $table = 'sw_menu_perfil';
    
    // Define los campos que se pueden llenar masivamente
    protected array $fillable = [
        'id_perfil',
        'id_menu'
    ];

    // Activa o desactiva el Soft Delete según tus necesidades en la tabla
    protected bool $useSoftDeletes = false; 
}
