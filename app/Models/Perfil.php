<?php

namespace App\Models;

class Perfil extends Model
{
    protected string $table = 'sw_perfil';
    protected string $primaryKey = 'id_perfil ';
    protected array $fillable = ['pe_nombre', 'pe_slug'];

}
