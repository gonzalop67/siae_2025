<?php

// Punto de entrada de la aplicación SIAE_2025
session_start(); // start session

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Archivo de constantes
require_once __DIR__ . '/../config/config.php';

// echo RUTA_APP;

require_once __DIR__ . '/../Core/helpers.php';

// Autoloader
require_once __DIR__ . '/../autoload.php';
// Incluir rutas
require_once __DIR__ . '/../routes/web.php';
