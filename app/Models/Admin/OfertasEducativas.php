<?php

namespace App\Models\Admin;

use App\Models\Model;

class OfertasEducativas extends Model
{
    protected string $table = 'ofertas_educativass';
    protected string $primaryKey = 'id';
    
    // Define los campos que se pueden llenar masivamente
    protected array $fillable = [];

    // Activa o desactiva el Soft Delete según tus necesidades en la tabla
    protected bool $useSoftDeletes = false; 
}
