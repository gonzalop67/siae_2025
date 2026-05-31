<?php

namespace App\Models;

use App\Models\Model;

class Oferta_educativa extends Model
{
    protected string $table = 'ofertas_educativas';
    protected string $primaryKey = 'id';
    
    // Define los campos que se pueden llenar masivamente
    protected array $fillable = [
        'nombre',
        'activo',
        'orden',
    ];

    // Activa o desactiva el Soft Delete según tus necesidades en la tabla
    protected bool $useSoftDeletes = false; 
}
