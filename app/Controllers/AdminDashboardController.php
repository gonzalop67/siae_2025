<?php

namespace App\Controllers;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
    }

    public function index()
    {
        $title = "Bienvenido a " . APP_NAME;
        echo $this->view('admin.dashboard', compact('title'));
    }
}