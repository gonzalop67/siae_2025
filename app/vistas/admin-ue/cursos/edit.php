<div class="container-fluid px-4">
    <div class="card mt-2 mb-4">
        <div class="card-header">
            <i class="fas fa-users me-1"></i>
            Editar Curso
        </div>
        <div class="card-body">
            <form id="formulario" action="" method="post">
                <input type="hidden" name="id_curso" value="<?= $datos['curso']->id_curso ?>">
                <div class="mb-3">
                    <label for="nombre" class="form-label requerido">Nombre:</label>
                    <input type="text" class="form-control" value="<?= $datos['curso']->cu_nombre ?>" name="nombre" id="nombre" required>
                    <p id="error-nombre" class="invalid-feedback">El nombre del curso debe contener de 4 a 64 caracteres alfabéticos y/o el caracter espacio en blanco.</p>
                </div>
                <div class="mb-3">
                    <label for="nombre_corto" class="form-label requerido">Nombre Corto:</label>
                    <input type="text" class="form-control" value="<?= $datos['curso']->cu_shortname ?>" name="nombre_corto" id="nombre_corto" required>
                    <p id="error-nombre_corto" class="invalid-feedback">El nombre corto del curso debe contener de 4 a 64 caracteres alfabéticos y/o el caracter espacio en blanco.</p>
                </div>
                <div class="mb-3">
                    <label for="abreviatura" class="form-label requerido">Abreviatura:</label>
                    <input type="text" class="form-control" value="<?= $datos['curso']->cu_abreviatura ?>" name="abreviatura" id="abreviatura" required>
                    <p id="error-abreviatura" class="invalid-feedback">La abreviatura del curso debe contener de 4 a 45 caracteres alfabéticos y/o el caracter espacio en blanco.</p>
                </div>
                <!-- <div class="mb-3">
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
                            <option value="<?= $periodo->id_periodo_lectivo ?>" <?= 1 ?>><?= $nombre ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <p id="error-periodo_lectivo" class="invalid-feedback">Debe seleccionar un periodo lectivo vigente.</p>
                </div> -->
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="subniveles" class="form-label requerido">Subniveles de Educación:</label>
                        <?php foreach ($datos['subniveles'] as $v) : ?>
                            <div class="control">
                                <label class="checkbox">
                                    <input type="checkbox" name="subniveles[]" value="<?= $v->id ?>" <?= (in_array($v->id, $datos['subnivelesCurso'])) ? 'checked' : '' ?>>
                                    <?= $v->nombre ?>
                                </label>
                            </div>
                        <?php endforeach ?>
                        <p id="error-subniveles" class="invalid-feedback">Debes seleccionar al menos un subnivel de educación...</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button id="btn-submit" type="submit" class="btn btn-primary btn-sm">Actualizar</button>
                        <a href="<?= RUTA_URL . "cursos" ?>" class="btn btn-success btn-sm"><i class="fa fa-backward"></i> Regresar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const base_url = "<?php echo RUTA_URL; ?>";
</script>
<script src="<?php echo RUTA_URL; ?>public/assets/js/pages/admin-ue/cursos/create.js"></script>