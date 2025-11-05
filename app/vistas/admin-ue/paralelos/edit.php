<div class="container-fluid px-4">
    <div class="card mt-2 mb-4">
        <div class="card-header">
            <i class="far fa-calendar me-1"></i>
            Editar Paralelo
        </div>
        <div class="card-body">
            <form id="formulario" action="" method="post">
                <input type="hidden" name="id_paralelo" value="<?= $datos['paralelo']->id_paralelo ?>">
                <div class="mb-3">
                    <label for="curso" class="form-label requerido">Curso:</label>
                    <select class="form-select" name="curso" id="curso" required>
                        <option value="">Seleccione...</option>
                        <?php
                        foreach ($datos['cursos'] as $curso) {
                            $nombre = $curso->es_figura . " - " . $curso->cu_nombre; 
                        ?>
                            <option value="<?= $curso->id_curso_subnivel ?>" <?= $datos['paralelo']->curso_subnivel_id == $curso->id_curso_subnivel ? 'selected' : '' ?>><?= $nombre ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <p id="error-curso" class="invalid-feedback">Debe seleccionar un curso.</p>
                </div>
                <div class="mb-3">
                    <label for="periodo_lectivo" class="form-label requerido">Periodo Lectivo:</label>
                    <select class="form-select" name="periodo_lectivo" id="periodo_lectivo" required>
                        <option value="">Seleccione...</option>
                        <?php
                        $meses_abrev = array(0, "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
                        foreach ($datos['periodos_lectivos'] as $periodo) {
                            $fecha_inicial = explode("-", $periodo->pe_fecha_inicio);
                            $fecha_final = explode("-", $periodo->pe_fecha_fin);
                            $nombre = "[" . $periodo->mo_nombre . "] " . $meses_abrev[(int)$fecha_inicial[1]] . " " . $fecha_inicial[0] . " - " . $meses_abrev[(int)$fecha_final[1]] . " " . $fecha_final[0];
                        ?>
                            <option value="<?= $periodo->id_periodo_lectivo ?>" <?= $datos['paralelo']->id_periodo_lectivo == $periodo->id_periodo_lectivo ? 'selected' : '' ?>><?= $nombre ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <p id="error-periodo_lectivo" class="invalid-feedback">Debe seleccionar un periodo lectivo vigente.</p>
                </div>
                <div class="mb-3">
                    <label for="jornada" class="form-label requerido">Jornada:</label>
                    <select class="form-select" name="jornada" id="jornada" required>
                        <option value="">Seleccione...</option>
                        <?php
                        foreach ($datos['jornadas'] as $jornada) {
                        ?>
                            <option value="<?= $jornada->id_jornada ?>" <?= $datos['paralelo']->id_jornada == $jornada->id_jornada ? 'selected' : '' ?>><?= $jornada->jo_nombre ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <p id="error-jornada" class="invalid-feedback">Debe seleccionar una jornada.</p>
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label requerido">Nombre:</label>
                    <input type="text" class="form-control text-uppercase" value="<?= $datos['paralelo']->pa_nombre ?>" name="nombre" id="nombre" required>
                    <p id="error-nombre" class="invalid-feedback">El nombre del paralelo debe contener de 1 a 5 caracteres alfabéticos sin espacios en blanco.</p>
                </div>
                <button id="btn-submit" type="submit" class="btn btn-primary">Actualizar</button>
                <a href="<?= RUTA_URL . "paralelos" ?>" class="btn btn-secondary">Regresar</a>
            </form>
        </div>
    </div>
</div>

<script>
    const base_url = "<?php echo RUTA_URL; ?>";
</script>
<script src="<?php echo RUTA_URL; ?>public/assets/js/pages/admin-ue/paralelos/create.js"></script>