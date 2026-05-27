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
}
