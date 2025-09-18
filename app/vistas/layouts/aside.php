<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading"><?= $_SESSION['nombrePerfil'] ?></div>
                <?php
                $uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
                $active = ($uriSegments[2] == $datos['dashboard']) ? 'active' : '';
                ?>
                <a href="<?= RUTA_URL . $datos['dashboard'] ?>/dashboard" class="nav-link <?= $active ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                    Dashboard
                </a>
                <?php
                $menuModelo = $this->modelo('Menu');
                $menusNivel1 = $menuModelo->listarMenusNivel1($_SESSION['id_perfil']);
                foreach ($menusNivel1 as $menu) {
                ?>
                    <?php if (count($menuModelo->listarMenusHijos($menu->id_menu)) > 0) { ?>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapse<?= $menu->mnu_texto ?>" aria-expanded="false" aria-controls="collapse<?= $menu->mnu_texto ?>">
                            <div class="sb-nav-link-icon"><i class="fas <?= $menu->mnu_icono == '' ? 'fa-link' : $menu->mnu_icono  ?>"></i></div>
                            <?= $menu->mnu_texto ?>
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapse<?= $menu->mnu_texto ?>" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <?php
                                foreach ($menuModelo->listarMenusHijos($menu->id_menu) as $menu2) {
                                    $uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
                                    $active = ($uriSegments[2] == $menu2->mnu_link) ? 'active' : '';
                                    $icono = "";
                                    if ($menu2->mnu_icono != "") {
                                        $icono = "<i class='fa fa-fw $menu2->mnu_icono'></i> ";
                                    }
                                ?>
                                    <a class="nav-link <?= $active ?>" href="<?php echo RUTA_URL . $menu2->mnu_link; ?>"><?= $menu2->mnu_texto; ?></a>
                                <?php } ?>
                            </nav>
                        </div>
                    <?php } else {
                        $uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
                        $active = ($uriSegments[2] == $menu->mnu_link) ? 'active' : '';
                    ?>
                        <a class="nav-link <?= $active ?>" href="<?= RUTA_URL . $menu->mnu_link; ?>">
                            <div class="sb-nav-link-icon"><i class="fas <?= $menu->mnu_icono == '' ? 'fa-link' : $menu->mnu_icono  ?>"></i></div>
                            <?= $menu->mnu_texto; ?>
                        </a>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </nav>
</div>