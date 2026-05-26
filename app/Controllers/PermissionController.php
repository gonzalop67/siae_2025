<?php

namespace App\Controllers;

use App\Models\Permiso;

class PermissionController extends Controller
{
    protected Permiso $permissionModel;

    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->permissionModel = new Permiso;
    }

    public function index()
    {
        $title = 'Permissions Admin';
        $search = trim($_GET['search'] ?? '');

        if ($search !== '') {
            // 1. Creamos la estructura SQL agrupada con paréntesis para proteger la lógica
            $this->permissionModel->where = "(nombre LIKE ? OR slug LIKE ? OR descripcion LIKE ?)";

            // 2. Preparamos los comodines de forma segura
            $term = "%{$search}%";

            // 3. Pasamos los valores al arreglo que procesará el prepare del ORM
            $this->permissionModel->values = [$term, $term, $term];
        }

        // El ORM inyectará de forma automática el ORDER BY y resolverá la paginación
        $permissions = $this->permissionModel
            ->orderBy('nombre')
            ->paginate(5);

        // return $permissions;

        return $this->view('admin.permissions.index', compact('permissions', 'title'));
    }
}
