<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;

use App\Models\Admin\Menu;
use App\Models\Admin\Perfil;
use App\Models\Admin\MenuPerfil;

class MenuController extends Controller
{
    protected Menu $menuModel;
    protected Perfil $perfilModel;
    protected MenuPerfil $menuPerfilModel;

    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->menuModel = new Menu;
        $this->perfilModel = new Perfil;
        $this->menuPerfilModel = new MenuPerfil;
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

            if (!empty($menus)) {
                // Retorna directamente el <ol> recursivo
                echo $this->renderNestableTree($menus);
            } else {
                echo '<div class="text-center text-muted py-4">No hay menús asignados a este perfil.</div>';
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
            $html .= '  <div class="dd-handle dd3-handle"></div>';
            $html .= '  <div class="dd3-content menu_link">';

            $html .= '    <a href="#" onclick="obtenerDatos(' . $menu["id_menu"] . ')" data-toggle="modal" data-target="#editarMenuModal">' . $menu["mnu_texto"] . '</a>';
            $html .= '    <a href="' . RUTA_URL . '/menus/delete/' . $menu["id_menu"] . '" class="eliminar-menu float-right" title="Eliminar este menú"><i class="text-danger fas fa-trash-alt"></i></a>';
            $html .= '  </div>';

            // RECURSIÓN REAL: Si tiene hijos, se llama a sí misma para procesar el sub-arreglo
            if ($hasChildren) {
                $html .= $this->renderNestableTree($menu['submenu']);
            }

            $html .= '</li>';
        }
        $html .= '</ol>';

        return $html;
    }

    public function guardar_orden_ajax()
    {
        // Verificar que la petición sea POST y contenga la estructura del menú
        if (isset($_POST['estructura'])) {
            $estructura = json_decode($_POST['estructura'], true);

            if (!empty($estructura)) {
                // Iniciamos la actualización recursiva desde la raíz (padre_id = 0)
                $this->actualizarPosicionesRecursivo($estructura, 0);

                echo json_encode([
                    'ok' => true,
                    'mensaje' => 'El orden de los menús se actualizó correctamente.'
                ]);
            } else {
                echo json_encode([
                    'ok' => false,
                    'mensaje' => 'La estructura enviada está vacía.'
                ]);
            }
            exit;
        }
    }

    /**
     * Función auxiliar recursiva para actualizar jerarquía y orden en la BD
     */
    private function actualizarPosicionesRecursivo(array $items, int $padreId)
    {
        foreach ($items as $indice => $item) {
            $idMenu = (int)$item['id'];
            $nuevoOrden = $indice + 1; // El orden inicia en 1 para la base de datos

            // 1. Actualizar el registro actual con su nuevo padre y su nueva posición
            // Ajusta los nombres de las columnas 'mnu_padre' y 'mnu_orden' según tu tabla
            $this->menuModel->actualizarOrdenYPadre($idMenu, $padreId, $nuevoOrden);

            // 2. Si este elemento tiene hijos (submenús), procesarlos recursivamente
            if (isset($item['children']) && !empty($item['children'])) {
                $this->actualizarPosicionesRecursivo($item['children'], $idMenu);
            }
        }
    }

    /**
     * Almacena un recurso recién creado en la base de datos.
     */
    public function store()
    {
        // Indicar al navegador/JS que la respuesta siempre será un JSON
        header('Content-Type: application/json');

        // 1. Capturar datos directamente de $_POST (compatible al 100% con FormData de JS)
        $input = $_POST ?? [];

        // 2. Validar datos de entrada
        if (!$this->menuModel->validate($input)) {
            return json_encode([
                'ok' => false,
                'errors' => $this->menuModel->errors
            ]);
        }

        // Limpiar espacios múltiples en el icono
        $icono = preg_replace('/\s+/', ' ', trim($input['mnu_icono'] ?? ''));

        $id_perfil = trim($input['id_perfil'] ?? '');

        // 3. Preparación del set de datos (limpiando espacios)
        $datos = [
            'mnu_texto' => trim($input['mnu_texto'] ?? ''),
            'mnu_link'  => trim($input['mnu_link'] ?? ''),
            'mnu_icono' => trim($icono ?? ''),
            'mnu_publicado' => 1,
            'id_perfil' => $id_perfil
        ];

        // 4. Persistencia con manejo de transacciones atómicas
        try {
            $this->menuModel->beginTransaction();
            // echo "<pre>"; print_r($datos); echo "</pre>"; die();

            // Ejecutamos la creación en la base de datos
            $menu = $this->menuModel->create($datos);

            // Captura del ID a través de tu método público
            $idMenu = $this->menuModel->getInsertId();
            if ($idMenu === 0 && is_array($menu)) {
                $idMenu = (int)($menu['id_menu'] ?? 0);
            }

            if ($idMenu === 0) {
                throw new \Exception("Error al procesar el identificador único del nuevo registro.");
            }

            // 3. Insertar la relación en la tabla puente (Bajo la misma transacción)
            $this->menuPerfilModel->create([
                'id_perfil' => $id_perfil,
                'id_menu' => $idMenu
            ]);

            // Confirmar cambios en la base de datos
            $this->menuModel->commit();

            return json_encode([
                'ok' => true,
                'mensaje' => 'Menú procesado con éxito.'
            ]);
        } catch (\Throwable $e) {
            // Deshace cualquier cambio si algo falla en el proceso
            $this->menuModel->rollBack();

            return json_encode([
                'ok' => false,
                'mensaje' => "Ocurrió un error inesperado: " . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtiene el registro par editarlo.
     */
    public function edit(int $id)
    {
        $menu = $this->menuModel->find($id);
        return $menu;
    }

    /**
     * Actualiza un recurso específico en la base de datos.
     */
    public function update(int $id)
    {
        // Indicar al navegador/JS que la respuesta siempre será un JSON
        header('Content-Type: application/json');

        // 1. Capturar datos directamente de $_POST (compatible al 100% con FormData de JS)
        $input = $_POST ?? [];

        // 2. Validar datos de entrada
        if (!$this->menuModel->validate($input, $id)) {
            return json_encode([
                'ok' => false,
                'errors' => $this->menuModel->errors
            ]);
        }

        // Limpiar espacios múltiples en el icono
        $icono = preg_replace('/\s+/', ' ', trim($input['mnu_icono'] ?? ''));

        // 3. Preparación del set de datos (limpiando espacios)
        $datos = [
            'mnu_texto' => trim($input['mnu_texto'] ?? ''),
            'mnu_link'  => trim($input['mnu_link'] ?? ''),
            'mnu_icono' => trim($icono ?? ''),
            'mnu_publicado' => trim($input['mnu_publicado'] ?? ''),
        ];

        // 4. Persistencia con manejo de transacciones atómicas
        try {
            $this->menuModel->beginTransaction();
            // echo "<pre>"; print_r($datos); echo "</pre>"; die();

            // Ejecutar actualización
            $this->menuModel->update($id, $datos);

            // Confirmar cambios en la base de datos
            $this->menuModel->commit();

            return json_encode([
                'ok' => true,
                'mensaje' => 'Menú procesado con éxito.'
            ]);
        } catch (\Throwable $e) {
            // Deshace cualquier cambio si algo falla en el proceso
            $this->menuModel->rollBack();

            return json_encode([
                'ok' => false,
                'mensaje' => "Ocurrió un error inesperado: " . $e->getMessage()
            ]);
        }
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
