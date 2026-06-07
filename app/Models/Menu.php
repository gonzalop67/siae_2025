<?php

namespace App\Models;

use App\Models\Model;

class Menu extends Model
{
    protected string $table = 'sw_menu';
    protected string $primaryKey = 'id_menu';

    // Define los campos que se pueden llenar masivamente
    protected array $fillable = [
        'mnu_texto',
        'mnu_enlace',
        'mnu_link',
        'mnu_nivel',
        'mnu_orden',
        'mnu_padre',
        'mnu_publicado',
        'mnu_icono',
    ];

    // Activa o desactiva el Soft Delete según tus necesidades en la tabla
    protected bool $useSoftDeletes = false;

    /**
     * Obtiene el árbol de menús autorizados relacionando sw_menu con sw_menu_perfil
     */
    public function getMenuByPerfil(int $id_perfil): array
    {
        // 1. CONSULTA CON JOIN: Conectamos la tabla puente usando id_menu
        $sql = "SELECT m.id_menu, m.mnu_texto, m.mnu_enlace, m.mnu_link, m.mnu_nivel, m.mnu_orden, m.mnu_padre, m.mnu_icono, m.mnu_publicado 
                FROM sw_menu m
                INNER JOIN sw_menu_perfil mp ON m.id_menu = mp.id_menu
                WHERE mp.id_perfil = ? AND m.mnu_publicado = 1
                ORDER BY m.mnu_nivel ASC, m.mnu_orden ASC";

        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param('i', $id_perfil);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        if (empty($rows)) {
            return [];
        }

        // 2. ARMADO DEL ÁRBOL DINÁMICO (Padres e Hijos)
        $menuTree = [];
        $submenus = [];

        foreach ($rows as $row) {
            if ((int)$row['mnu_padre'] === 0) {
                $row['submenu'] = [];
                $menuTree[$row['id_menu']] = $row;
            } else {
                $submenus[] = $row;
            }
        }

        // 3. ASOCIACIÓN DE SUBMENÚS
        foreach ($submenus as $sub) {
            $padreId = $sub['mnu_padre'];
            if (isset($menuTree[$padreId])) {
                $menuTree[$padreId]['submenu'][] = $sub;
            } else {
                // Si el padre no está asignado a este perfil pero el hijo sí, 
                // lo mostramos en la raíz para no perder el acceso
                $sub['submenu'] = [];
                $menuTree[$sub['id_menu']] = $sub;
            }
        }

        return array_values($menuTree);
    }

    public function getMenus(int $id_perfil)
    {
        // Filtramos directamente por el ID del perfil seleccionado
        $sql = "SELECT m.*, pe_nombre FROM `sw_menu` m
            INNER JOIN `sw_menu_perfil` mp ON m.id_menu = mp.id_menu
            INNER JOIN `sw_perfil` p ON p.id_perfil = mp.id_perfil 
            WHERE mp.id_perfil = ?
            ORDER BY m.mnu_padre, m.mnu_orden";

        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $id_perfil);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        $menuTree = [];
        $submenus = [];

        // 1. SEPARAR PADRES E HIJOS
        foreach ($rows as $row) {
            if ((int)$row['mnu_padre'] === 0) {
                $row['submenu'] = [];
                $menuTree[$row['id_menu']] = $row;
            } else {
                $submenus[] = $row;
            }
        }

        // 2. ASOCIACIÓN DE SUBMENÚS (Soporta múltiples niveles mediante referencia)
        foreach ($submenus as $sub) {
            $padreId = $sub['mnu_padre'];
            if (isset($menuTree[$padreId])) {
                $menuTree[$padreId]['submenu'][] = $sub;
            } else {
                // Si es un submenú profundo (nieto), buscamos a su padre dentro de los submenús ya procesados
                $asignado = false;
                foreach ($menuTree as &$padreRaiz) {
                    if ($this->insertarEnHijo($padreRaiz, $sub)) {
                        $asignado = true;
                        break;
                    }
                }
                // Si el padre no existe en el perfil, se muestra en la raíz
                if (!$asignado) {
                    $sub['submenu'] = [];
                    $menuTree[$sub['id_menu']] = $sub;
                }
            }
        }

        return array_values($menuTree);
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

    public function actualizarOrdenYPadre(int $idMenu, int $padreId, int $orden)
    {
        // Ejemplo con PDO clásico:
        $sql = "UPDATE sw_menu 
            SET mnu_padre = ?, mnu_orden = ? 
            WHERE id_menu = ?";

        $stmt = $this->connection->prepare($sql);

        $stmt->bind_param('iii', $padreId, $orden, $idMenu);
        $stmt->execute();
        $stmt->close();
    }

    public function validate(array $data, ?int $id = null): bool
    {
        $this->errors = [];

        // Limpiar espacios múltiples en el texto
        $texto = preg_replace('/\s+/', ' ', trim($data['mnu_texto'] ?? ''));

        // CORREGIDO: Rescatar y limpiar la descripción de la data recibida
        $enlace = trim($data['mnu_link'] ?? '');

        // -------------------------------------------------------------
        // VALIDACIÓN: TEXTO
        // -------------------------------------------------------------
        if (empty($texto)) {
            $this->errors['textou'] = "El campo Texto es obligatorio.";
        } elseif (!preg_match('/^([a-zA-Z ñáéíóúÑÁÉÍÓÚ]{3,64})$/i', $texto)) {
            $this->errors['textou'] = "El texto del menú tiene que ser de 3 a 64 caracteres (alfabéticos con acentos y espacio).";
        } elseif ($this->exists('mnu_texto', $texto, $id)) {
            $this->errors['textou'] = "Ya existe el Texto del Menú en la base de datos.";
        }

        // -------------------------------------------------------------
        // VALIDACIÓN: ENLACE
        // -------------------------------------------------------------
        if (empty($enlace)) {
            $this->errors['enlaceu'] = "El campo Enlace es obligatorio.";
        } elseif ($this->exists('mnu_link', $enlace, $id)) {
            $this->errors['enlaceu'] = "Ya existe el Enlace del Menú en la base de datos.";
        }

        return empty($this->errors);
    }
}
