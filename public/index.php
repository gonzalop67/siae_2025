<?php

// Punto de entrada de la aplicación SIAE_2025

// Archivo de constantes
require_once __DIR__ . '/../config/config.php';

// echo RUTA_APP;

require_once __DIR__ . '/../Core/helpers.php';

// Autoloader
require_once __DIR__ . '/../autoload.php';
// Incluir rutas
require_once __DIR__ . '/../routes/web.php';
