<?php

namespace App\Controllers;

use App\Controllers\Controller;

use App\Models\Menu;
use App\Models\Perfil;

class MenuController extends Controller
{
    protected Menu $menuModel;
    protected Perfil $perfilModel;

    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->menuModel = new Menu;
        $this->perfilModel = new Perfil;
    }

    /**
     * Muestra el listado del recurso.
     */
    public function index()
    {
        $title = 'Menús | ' . APP_NAME;

        $perfiles = $this->perfilModel->orderBy('pe_nombre')->get();

        return $this->view('admin.menus.index', compact('title', 'perfiles'));
    }

    public function get_menu_ajax()
    {
        if (isset($_POST['perfil_id'])) {
            $id_perfil = (int)$_POST['perfil_id'];

            $menus = $this->menuModel->getMenus($id_perfil);

            // return $menus;

            if (!empty($menus)) {
                // Llamamos a la función recursiva que ya creaste
                echo $this->renderNestableTree($menus);
            } else {
                echo '<div class="text-center">No hay menús asignados a este perfil.</div>';
            }
            exit;
        }
    }

    private function renderNestableTree(array $menus)
    {
        if (empty($menus)) return '';

        $html = '<ol class="dd-list">';
        foreach ($menus as $menu) {
            $hasChildren = !empty($menu['submenu']);
            $html .= '<li class="dd-item dd3-item" data-id="' . $menu["id_menu"] . '">';
            $html .= '<div class="dd-handle dd3-handle"></div>';
            $html .= '<div class="dd3-content menu_link">';
            $html .= '<a href="#" onclick="obtenerDatos(' . $menu["id_menu"] . ')" data-toggle="modal" data-target="#editarMenuModal">(' . $menu["pe_nombre"] . ') ' . $menu["mnu_texto"] . '</a>';
            $html .= '<a href="' . RUTA_URL . '/menus/delete/' . $menu["id_menu"] . '" class="eliminar-menu float-right" title="Eliminar este menú"><i class="text-danger fas fa-trash-alt"></i></a>';
            $html .= '</div>';
            if ($hasChildren) {
                $html .= '<ol class="dd-list">';
                foreach ($menu['submenu'] as $menu2) {
                    $html .= '<li class="dd-item dd3-item" data-id="' . $menu2["id_menu"] . '">';
                    $html .= '<div class="dd-handle dd3-handle"></div>';
                    $html .= '<div class="dd3-content menu_link">';
                    $html .= '<a href="#" onclick="obtenerDatos(' . $menu2['id_menu'] . ')" data-toggle="modal" data-target="#editarMenuModal">' . $menu2["mnu_texto"] . '</a>';
                    $html .= '<a href="' . RUTA_URL . '/menus/delete/' . $menu2["id_menu"] . '" class="eliminar-menu float-right" title="Eliminar este menú"><i class="text-danger fas fa-trash-alt"></i></a>';
                    $html .= '</div>';
                    $html .= '</li>';
                }
                $html .= '</ol>';
            }
            $html .= '</li>';
        }
        $html .= '</ol>';

        return $html;
    }

    // Función auxiliar interna para soportar árboles de más de 2 niveles (Nietos)
    private function insertarEnHijo(&$padre, $sub)
    {
        if (isset($padre['id_menu']) && $padre['id_menu'] == $sub['mnu_padre']) {
            $sub['submenu'] = [];
            $padre['submenu'][] = $sub;
            return true;
        }
        if (!empty($padre['submenu'])) {
            foreach ($padre['submenu'] as &$hijo) {
                if ($this->insertarEnHijo($hijo, $sub)) {
                    return true;
                }
            }
        }
        return false;
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
