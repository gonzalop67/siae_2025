<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="<?= RUTA_URL ?>Admin/dashboard">SIAE <?= isset($nombrePeriodoLectivo) ? $nombrePeriodoLectivo : '' ?></a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            <select id="cboPeriodosL" name="cboPeriodosL" class="form-select">
                <option value="">Cambiar Periodo Lectivo...</option>
                <!-- Aquí van los periodos lectivos definidos en la BDD -->
                <?php
                $meses_abrev = array(0, "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
                $modalidadModelo = $this->modelo('Modalidad');
                $modalidades = $modalidadModelo->obtenerModalidades();
                $periodoLectivoModelo = $this->modelo('PeriodoLectivo');
                foreach ($modalidades as $modalidad) {
                    $code = $modalidad->id_modalidad;
                    $name = $modalidad->mo_nombre;
                ?>
                    <optgroup label='<?php echo $name; ?>'>
                        <?php $periodos = $periodoLectivoModelo->obtenerPeriodosL($code);
                        foreach ($periodos as $periodo) {
                            $code2 = $periodo->id_periodo_lectivo;
                            $fecha_inicial = explode("-", $periodo->pe_fecha_inicio);
                            $fecha_final = explode("-", $periodo->pe_fecha_fin);
                            $name2 = $meses_abrev[(int)$fecha_inicial[1]] . " " . $fecha_inicial[0] . " - " . $meses_abrev[(int)$fecha_final[1]] . " " . $fecha_final[0] . " [" . $periodo->pe_descripcion . "]";
                        ?>
                            <option value="<?php echo $code2; ?>"><?php echo $name2; ?></option>
                        <?php } ?>
                    </optgroup>
                <?php } ?>
            </select>
            <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-rotate"></i></button>
        </div>
    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item">
            <a class="nav-link d-inline-block" style="width: 75px;" id="navbarComments" href="#" role="button" aria-expanded="false">
                <i class="fa-solid fa-comments"></i>
                <span class="badge badge-danger"><?= $nro_comentarios ?></span>
            </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img class="avatar__img" src="<?= $avatar_user ?>" alt="avatar user"> 
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="#!">Settings</a></li>
                <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                <li>
                    <hr class="dropdown-divider" />
                </li>
                <li><a class="dropdown-item" href="<?php echo RUTA_URL ?>Auth/logout">Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>