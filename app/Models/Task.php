<?php

namespace App\Models;

use App\Models\Model;

class Task extends Model
{
    protected string $table = 'sw_tarea';
    protected string $primaryKey = 'id';
    
    // Define los campos que se pueden llenar masivamente
    protected array $fillable = [
        'tarea',
        'hecho',
    ];

    // Activa o desactiva el Soft Delete según tus necesidades en la tabla
    protected bool $useSoftDeletes = true; 

    public function validate(array $data, ?int $id = null): bool
    {
        $this->errors = [];

        // Limpiar espacios múltiples en la nueva tarea
        $tarea = preg_replace('/\s+/', ' ', trim($data['tarea'] ?? ''));

        // -------------------------------------------------------------
        // VALIDACIÓN: TAREA
        // -------------------------------------------------------------
        if (empty($tarea)) {
            $this->errors['tarea'] = "Debes ingresar una nueva tarea.";
        } elseif ($this->exists('tarea', $tarea, $id)) {
            $this->errors['tarea'] = "Ya existe la Tarea en la base de datos.";
        }

        return empty($this->errors);
    }
}
