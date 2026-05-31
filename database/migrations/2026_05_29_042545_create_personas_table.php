<?php

use App\Models\Model;

class CreatePersonasTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS personas (
            id_persona INT AUTO_INCREMENT PRIMARY KEY,
            -- Agrega tus columnas aquí
            documento_identidad VARCHAR(15) NOT NULL UNIQUE,
            nombres VARCHAR(50) NOT NULL,
            apellidos VARCHAR(50) NOT NULL,
            fecha_nacimiento DATE NOT NULL,
            genero ENUM('M', 'F', 'Otro') NOT NULL,
            telefono VARCHAR(20),
            direccion VARCHAR(250),
            correo_personal VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->connection->query($sql);
    }

    /**
     * Revierte la migración (Eliminar tablas).
     */
    public function down(): void
    {
        $sql = "DROP TABLE IF EXISTS personas;";
        $this->connection->query($sql);
    }
}
