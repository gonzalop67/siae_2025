<?php

use App\Models\Model;

class CreateInstitutionTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS institution (
            id INT AUTO_INCREMENT PRIMARY KEY,
            -- Agrega tus columnas aquí
            nombre VARCHAR(64) NOT NULL,
            direccion VARCHAR(128) NOT NULL,
            telefono VARCHAR(64) NOT NULL,
            regimen VARCHAR(16) NOT NULL,
            nombre_rector VARCHAR(45) NOT NULL,
            genero_rector CHAR(1) DEFAULT 'M',
            nombre_vicerrector VARCHAR(45) NULL,
            genero_vicerrector CHAR(1) NULL,
            nombre_secretario VARCHAR(45) NULL,
            genero_secretario CHAR(1) NULL,
            email VARCHAR(64) NOT NULL,
            url VARCHAR(64) NOT NULL,
            logo VARCHAR(64) NOT NULL,
            amie VARCHAR(16) NOT NULL,
            ciudad VARCHAR(64) NOT NULL,
            copiar_y_pegar TINYINT(1) DEFAULT 1,
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
        $sql = "DROP TABLE IF EXISTS institution;";
        $this->connection->query($sql);
    }
}
