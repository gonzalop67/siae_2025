<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;

use App\Models\Modalidad;

class ModalidadController extends Controller
{
    protected Modalidad $modalidadModel;

    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->modalidadModel = new Modalidad;
    }

    /**
     * Muestra el listado del recurso.
     */
    public function index()
    {
        $title = 'Listado de Modalidades';

        $search = trim($_GET['search'] ?? '');

        if ($search !== '') {
            // 1. Creamos la estructura SQL agrupada con paréntesis para proteger la lógica
            $this->modalidadModel->where = "(nombre LIKE ?)";

            // 2. Preparamos los comodines de forma segura
            $term = "%{$search}%";

            // 3. Pasamos los valores al arreglo que procesará el prepare del ORM
            $this->modalidadModel->values = [$term];
        }

        // El ORM inyectará de forma automática el ORDER BY y resolverá la paginación
        $modalidades = $this->modalidadModel
            ->orderBy('orden')
            ->paginate(5);

        return $this->view('admin.modalidades.index', compact('modalidades', 'title'));
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     */
    public function create()
    {
        $title = 'Crear ModalidadeController';
        return $this->view('admin.modalidades.create', compact('title'));
    }

    /**
     * Almacena un recurso recién creado en la base de datos.
     */
    public function store()
    {
        // $this->model->create($_POST);
        // return redirect('/modalidade');
    }

    /**
     * Muestra un recurso específico.
     */
    public function show($id)
    {
        // $data = $this->model->find($id);
        // return $this->view('admin.modalidade.show', compact('data'));
    }

    /**
     * Muestra el formulario para editar un recurso específico.
     */
    public function edit($id)
    {
        $title = 'Editar ModalidadeController';
        // $data = $this->model->find($id);
        // return $this->view('admin.modalidade.edit', compact('data', 'title'));
    }

    /**
     * Actualiza un recurso específico en la base de datos.
     */
    public function update($id)
    {
        // $this->model->update($id, $_POST);
        // return redirect('/modalidade');
    }

    /**
     * Elimina un recurso específico de la base de datos.
     */
    public function destroy($id)
    {
        // $this->model->delete($id);
        // return redirect('/modalidade');
    }
}
