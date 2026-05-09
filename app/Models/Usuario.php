<?php

namespace App\Models;

class Usuario extends Model
{
    protected string $table = 'sw_usuario';
    protected array $fillable = [
        'institucion_id', 
        'us_login',
        'us_email',
        'us_password',
        'request_password',
        'token_password',
        'expired_session',
        'us_activo'
    ];

}