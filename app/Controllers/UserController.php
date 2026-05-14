<?php 

namespace App\Controllers;

class UserController extends Controller
{

    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
    }

    public function index()
    {
        return $this->view('');
    }
}
?>