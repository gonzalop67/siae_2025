<?php

namespace App\Models;

class Permiso extends Model
{
    protected string $table = 'sw_permiso';
    protected string $primaryKey = 'id_permiso';
    protected array $fillable = ['nombre', 'slug', 'descripcion'];

}