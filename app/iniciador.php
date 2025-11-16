<?php

    //Cargamos librerias
    require_once 'config/configurar.php';
    require_once 'helpers/helpers.php';
    require_once 'fpdf16/fpdf.php';

    //Autoload php
    spl_autoload_register(function($nombreClase){
        require_once 'librerias/' . $nombreClase . '.php';
    });