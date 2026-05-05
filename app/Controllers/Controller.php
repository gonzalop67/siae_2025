<?php

namespace App\Controllers;

use Core\MiniBlade;

class Controller
{
    protected MiniBlade $blade;

    public function __construct()
    {
        // Configuramos el motor una sola vez
        $this->blade = new MiniBlade(RUTA_APP . '/resources/views');
    }

    // Métodos comunes para controladores
    protected function view(string $route, array $data = [])
    {
        extract($data);

        return $this->blade->render($route, $data);
    }

    protected function json(mixed $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
