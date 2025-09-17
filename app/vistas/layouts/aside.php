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
                

            </div>
        </div>
    </nav>
</div>