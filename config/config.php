<?php
session_start(); // start session

//Configuración de acceso a la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'colegion_1');
define('DB_PASS', 'AQSWDE123');
define('DB_NAME', 'colegion_1');

// define global constants

//Ruta de la aplicación
define('RUTA_APP', dirname(dirname(__FILE__)));

define('APP_NAME', 'SIAE_2025');

define('RUTA_URL', 'http://localhost/siae_2025'); // the home url of the website