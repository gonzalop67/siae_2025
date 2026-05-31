<?php

use App\Models\Model;

class CreateModalidadesTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS modalidades (
            id_modalidad INT AUTO_INCREMENT PRIMARY KEY,
            -- Agrega tus columnas aquí
            nombre varchar(64) NOT NULL,
            activo tinyint(1) UNSIGNED NOT NULL,
            orden int(2) UNSIGNED NOT NULL DEFAULT 0,
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
        $sql = "DROP TABLE IF EXISTS modalidades;";
        $this->connection->query($sql);
    }
}
