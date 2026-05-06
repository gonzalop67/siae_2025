<?php

namespace App\Controllers;

use App\Models\Institucion;
use App\Models\Perfil;

class LoginController extends Controller
{
    private Perfil $perfilModel;
    private Institucion $institucionModel;

    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->institucionModel = new Institucion;
        $this->perfilModel = new Perfil;
    }

    public function showLoginForm()
    {
        $institucion = $this->institucionModel
        ->select('in_nombre')
        ->orderBy('id_institucion')
        ->first();
        $nom_institucion = $institucion['in_nombre'];
        $perfiles = $this->perfilModel->orderBy('pe_nombre')->get();
        return $this->view('auth.login', compact('nom_institucion', 'perfiles'));
    }
}