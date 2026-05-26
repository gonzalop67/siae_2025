<?php

namespace App\Controllers;

use App\Controllers\Controller;

class MenuController extends Controller
{
    /**
     * Muestra el listado del recurso.
     */
    public function index()
    {
        $title = 'Listado de Menús';
        // $data = $this->model->paginate(15);
        // return $this->view('admin.menu.index', compact('data', 'title'));
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     */
    public function create()
    {
        $title = 'Crear Menú';
        // return $this->view('admin.menu.create', compact('title'));
    }

    /**
     * Almacena un recurso recién creado en la base de datos.
     */
    public function store()
    {
        // $this->model->create($_POST);
        // return redirect('/menu');
    }

    /**
     * Muestra un recurso específico.
     */
    public function show(int $id)
    {
        // $data = $this->model->find($id);
        // return $this->view('admin.menu.show', compact('data'));
    }

    /**
     * Muestra el formulario para editar un recurso específico.
     */
    public function edit(int $id)
    {
        $title = 'Editar Menú';
        // $data = $this->model->find($id);
        // return $this->view('admin.menu.edit', compact('data', 'title'));
    }

    /**
     * Actualiza un recurso específico en la base de datos.
     */
    public function update(int $id)
    {
        // $this->model->update($id, $_POST);
        // return redirect('/menu');
    }

    /**
     * Elimina un recurso específico de la base de datos.
     */
    public function destroy(int $id)
    {
        // $this->model->delete($id);
        // return redirect('/menu');
    }
}
