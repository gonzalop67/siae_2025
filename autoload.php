<?php

spl_autoload_register(function ($clase) {
    $ruta = '../' . str_replace("\\", "/", $clase) . ".php";

    // Línea de depuración:
    // echo "Buscando en: " . realpath($ruta) . "<br>"; 

    if (file_exists($ruta)) {
        require_once $ruta;
    } else {
        die("No se pudo cargar la clase {$clase}");
    }
});
