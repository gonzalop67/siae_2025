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

    // Actualización del estado del campo hecho
    public function update_done(int $id, bool $done): bool
    {
        $sql = "UPDATE {$this->table} SET hecho = ? WHERE {$this->primaryKey} = ?";

        // Convertir el booleano explícitamente a un entero (true = 1, false = 0)
        $doneInt = $done ? 1 : 0;

        // Ejecutar la consulta pasando ambos valores como enteros ('ii')
        $this->query($sql, [$doneInt, $id], 'ii');

        // Retornar verdadero si la consulta se ejecutó sin lanzar excepciones
        return true;
    }
}
