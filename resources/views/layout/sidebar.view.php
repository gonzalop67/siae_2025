<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo RUTA_URL; ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">
            <?php echo isset($_SESSION['nombreInstitucion']) ? $_SESSION['nombreInstitucion'] : APP_NAME; ?>
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
            $finalMenuUrl = (strpos($menuLink, '/') === 0) ? RUTA_URL . $menuLink : RUTA_URL . '/' . $menuLink;
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
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" 
                       data-target="#collapse<?php echo $menu['id_menu']; ?>" 
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
                                $finalSubUrl = (strpos($subLink, '/') === 0) ? RUTA_URL . $subLink : RUTA_URL . '/' . $subLink;
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
