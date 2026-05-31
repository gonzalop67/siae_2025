<?php

namespace App\Models;

use App\Models\Model;

class Modalidad extends Model
{
    protected string $table = 'modalidades';
    protected string $primaryKey = 'id_modalidad';
    
    // Define los campos que se pueden llenar masivamente
    protected array $fillable = [
        'nombre',
        'activo',
        'orden',
    ];

    // Activa o desactiva el Soft Delete según tus necesidades en la tabla
    protected bool $useSoftDeletes = false; 
}
