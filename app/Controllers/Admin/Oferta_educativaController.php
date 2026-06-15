<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;

use App\Models\Admin\OfertasEducativas;

class Oferta_educativaController extends Controller
{
    protected OfertasEducativas $ofertaEducativaModel;

    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->ofertaEducativaModel = new OfertasEducativas;
    }
    
    /**
     * Muestra el listado del recurso.
     */
    public function index()
    {
        $title = 'Listado de Ofertas Educativas';
        $search = trim($_GET['search'] ?? '');

        if ($search !== '') {
            // 1. Creamos la estructura SQL agrupada con paréntesis para proteger la lógica
            $this->ofertaEducativaModel->where = "(nombre LIKE ?)";

            // 2. Preparamos los comodines de forma segura
            $term = "%{$search}%";

            // 3. Pasamos los valores al arreglo que procesará el prepare del ORM
            $this->ofertaEducativaModel->values = [$term];
        }

        // El ORM inyectará de forma automática el ORDER BY y resolverá la paginación
        $ofertas_educativas = $this->ofertaEducativaModel
            ->orderBy('nombre')
            ->paginate(5);
            
        return $this->view('admin.ofertas_educativas.index', compact('ofertas_educativas', 'title'));
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     */
    public function create()
    {
        $title = 'Crear Ofertas Educativas';
        return $this->view('admin.ofertas_educativas.create', compact('title'));
    }

    /**
     * Almacena un recurso recién creado en la base de datos.
     */
    public function store()
    {
        $this->ofertaEducativaModel->create($_POST);
        return redirect('/ofertas_educativas');
    }

    /**
     * Muestra un recurso específico.
     */
    public function show($id)
    {
        $data = $this->ofertaEducativaModel->find($id);
        return $this->view('admin.ofertas_educativas.show', compact('data'));
    }

    /**
     * Muestra el formulario para editar un recurso específico.
     */
    public function edit($id)
    {
        $title = 'Editar Ofertas Educativas';
        $data = $this->ofertaEducativaModel->find($id);
        return $this->view('admin.ofertas_educativas.edit', compact('data', 'title'));
    }

    /**
     * Actualiza un recurso específico en la base de datos.
     */
    public function update($id)
    {
        $this->ofertaEducativaModel->update($id, $_POST);
        return redirect('/ofertas_educativas');
    }

    /**
     * Elimina un recurso específico de la base de datos.
     */
    public function destroy($id)
    {
        $this->ofertaEducativaModel->delete($id);
        return redirect('/ofertas_educativas');
    }
}
