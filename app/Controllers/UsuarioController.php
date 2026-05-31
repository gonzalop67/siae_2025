<?php

namespace App\Controllers;

use App\Controllers\Controller;

class UsuarioController extends Controller
{
    /**
     * Muestra el listado del recurso.
     */
    public function index()
    {
        $title = 'Listado de UsuarioController';
$data = $this->model->paginate(15);
        // return $this->view('admin.usuario.index', compact('data', 'title'));
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     */
    public function create()
    {
        $title = 'Crear UsuarioController';
        // return $this->view('admin.usuario.create', compact('title'));
    }

    /**
     * Almacena un recurso recién creado en la base de datos.
     */
    public function store()
    {
$this->model->create($_POST);
return redirect('/usuario');
    }

    /**
     * Muestra un recurso específico.
     */
    public function show($id)
    {
$data = $this->model->find($id);
        // return $this->view('admin.usuario.show', compact('data'));
    }

    /**
     * Muestra el formulario para editar un recurso específico.
     */
    public function edit($id)
    {
        $title = 'Editar UsuarioController';
$data = $this->model->find($id);
        // return $this->view('admin.usuario.edit', compact('data', 'title'));
    }

    /**
     * Actualiza un recurso específico en la base de datos.
     */
    public function update($id)
    {
$this->model->update($id, $_POST);
return redirect('/usuario');
    }

    /**
     * Elimina un recurso específico de la base de datos.
     */
    public function destroy($id)
    {
$this->model->delete($id);
return redirect('/usuario');
    }
}
