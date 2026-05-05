<?php

namespace App\Controllers;

use App\Models\Perfil;

class LoginController extends Controller
{
    private Perfil $perfilModel;

    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->perfilModel = new Perfil;
    }

    public function showLoginForm()
    {
        $perfiles = $this->perfilModel->all();
        return $this->view('auth.login', compact('perfiles'));
    }
}