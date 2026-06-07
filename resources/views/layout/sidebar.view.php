<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <!-- Perfil del Usuario en el Sidebar -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center px-3" href="perfil.html">

        <!-- Contenedor de la Foto (Mantiene su espacio a la izquierda) -->
        <div class="sidebar-brand-icon mr-2">
            <img src="{{ RUTA_URL }}/public/uploads/{{ $_SESSION['us_foto'] ?? 'default.png' }}" alt="Foto de perfil" class="img-profile rounded-circle border border-white"
                style="width: 42px; height: 42px; object-fit: cover; flex-shrink: 0;">
        </div>

        <!-- Contenedor de Texto: Fuerza dos líneas centrándolas vertical y horizontalmente -->
        <div class="sidebar-brand-text d-flex flex-column align-items-center text-center lh-125"
            style="line-height: 1.2;">
            <span class="font-weight-bold" style="font-size: 0.8rem; text-transform: none; letter-spacing: 0;">
                {{ APP_NAME }}
            </span>
            <span class="text-white-50" style="font-size: 0.7rem; text-transform: none; letter-spacing: 0;">
                {{ $_SESSION['nombrePerfil'] }}
            </span>
        </div>

    </a>

    <!-- Divider Fijo Inicial -->
    <hr class="sidebar-divider my-0">

    <!-- Renderizado Dinámico de Menús desde sw_menu -->
    <?php if (!empty($menuItems) && is_array($menuItems)): ?>
    <?php foreach ($menuItems as $index => $menu): ?>

    <hr class="sidebar-divider">

    <?php
    // CORRECCIÓN VITAL: Nos aseguramos de agregar el "/" si la ruta no lo tiene al inicio
    $menuLink = $menu['mnu_link'];
    $finalMenuUrl = strpos($menuLink, '/') === 0 ? RUTA_URL . $menuLink : RUTA_URL . '/' . $menuLink;
    ?>

    <?php if (empty($menu['submenu'])): ?>
    <!-- ENLACE SIMPLE (Dashboard, Permisos, Usuarios, etc.) -->
    <li class="nav-item">
        <a class="nav-link" href="<?php echo $finalMenuUrl; ?>">
            <i class="<?php echo $menu['mnu_icono']; ?>"></i>
            <span><?php echo $menu['mnu_texto']; ?></span>
        </a>
    </li>
    <?php else: ?>
    <!-- MENÚ DESPLEGABLE (Components, Utilities, Pages, etc.) -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse<?php echo $menu['id_menu']; ?>"
            aria-expanded="false" aria-controls="collapse<?php echo $menu['id_menu']; ?>">
            <i class="<?php echo $menu['mnu_icono']; ?>"></i>
            <span><?php echo $menu['mnu_texto']; ?></span>
        </a>
        <div id="collapse<?php echo $menu['id_menu']; ?>" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <?php foreach ($menu['submenu'] as $sub): ?>
                <?php
                // Aplicamos la misma corrección de seguridad para los submenús
                $subLink = $sub['mnu_link'];
                $finalSubUrl = strpos($subLink, '/') === 0 ? RUTA_URL . $subLink : RUTA_URL . '/' . $subLink;
                ?>
                <a class="collapse-item" href="<?php echo $finalSubUrl; ?>">
                    <?php if (!empty($sub['mnu_icono'])): ?>
                    <i class="<?php echo $sub['mnu_icono']; ?> mr-1"></i>
                    <?php endif; ?>
                    <?php echo $sub['mnu_texto']; ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </li>
    <?php endif; ?>

    <?php endforeach; ?>
    <?php endif; ?>

    <!-- Divider Fijo Final -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
