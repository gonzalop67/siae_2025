<div class="container-fluid px-4">
    <div class="card mt-2 mb-4">
        <div class="card-header">
            <i class="fa fa-graduation-cap me-1"></i>
            Editar Asignatura
        </div>
        <div class="card-body">
            <form id="formulario" action="" method="post">
                <input type="hidden" name="id_asignatura" value="<?= $datos['asignatura']->id_asignatura ?>">
                <div class="mb-3">
                    <label for="areas" class="form-label">Áreas:</label>
                    <select class="form-select" id="areas" name="areas">
                        <option value="">Seleccione...</option>
                        <?php
                        foreach ($datos['areas'] as $area) {
                        ?>
                            <option value="<?= $area->id_area ?>" <?= $area->id_area == $datos['asignatura']->id_area ? 'selected' : '' ?>><?= $area->ar_nombre ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <p id="error-areas" class="invalid-feedback">Debe seleccionar un Área...</p>
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" class="form-control text-uppercase" value="<?= $datos['asignatura']->as_nombre ?>" name="nombre" id="nombre" required>
                    <p id="error-nombre" class="invalid-feedback">El nombre de la asignatura debe contener de 4 a 64 caracteres alfabéticos y/o el caracter espacio en blanco.</p>
                </div>
                <div class="mb-3">
                    <label for="abreviatura" class="form-label">Abreviatura:</label>
                    <input type="text" class="form-control text-uppercase" value="<?= $datos['asignatura']->as_abreviatura ?>" name="abreviatura" id="abreviatura" required>
                    <p id="error-abreviatura" class="invalid-feedback">La abreviatura de la asignatura debe contener de 4 a 8 caracteres alfabéticos y/o el caracter punto.</p>
                </div>
                <div class="mb-3">
                    <label for="tipos_asignatura" class="form-label">Tipo de Asignatura:</label>
                    <select class="form-select" id="tipos_asignatura" name="tipos_asignatura">
                        <option value="">Seleccione...</option>
                        <?php
                        foreach ($datos['tipos_asignatura'] as $tipo) {
                        ?>
                            <option value="<?= $tipo->id_tipo_asignatura ?>" <?= $tipo->id_tipo_asignatura == $datos['asignatura']->id_tipo_asignatura ? 'selected' : '' ?>><?= $tipo->ta_descripcion ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <p id="error-tipos_asignatura" class="invalid-feedback">Debe seleccionar un Tipo de Asignatura...</p>
                </div>
                <button id="btn-submit" type="submit" class="btn btn-primary">Actualizar</button>
                <a href="<?= RUTA_URL . "asignaturas" ?>" class="btn btn-secondary">Regresar</a>
            </form>
        </div>
    </div>
</div>

<script>
    const base_url = "<?php echo RUTA_URL; ?>";
</script>
<script src="<?php echo RUTA_URL; ?>public/assets/js/pages/admin/asignaturas/create.js"></script>