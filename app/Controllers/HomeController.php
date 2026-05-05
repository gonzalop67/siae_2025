<?php

namespace App\Controllers;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
    }

    public function index()
    {
        $title = "Bienvenido a {APP_NAME}";
        return $this->view('home', compact('title'));
    }
}
