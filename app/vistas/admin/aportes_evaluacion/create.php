<div class="container-fluid px-4">
    <div class="card mt-2 mb-4">
        <div class="card-header">
            <i class="fa-solid fa-marker me-1"></i>
            Crear Nueva Aporte de Evaluación

            <a href="<?= RUTA_URL . "aportes_evaluacion" ?>" class="btn btn-success btn-sm rounded-0 float-end"><i class="fa fa-backward"></i> Regresar</a>
        </div>
        <div class="card-body">
            <form id="formulario" action="" method="post">
                <div class="mb-3">
                    <label for="tipos_aporte" class="form-label form-control-sm requerido">Tipo de Aporte de Evaluación:</label>
                    <select class="form-select form-control-sm" id="tipos_aporte" name="tipos_aporte">
                        <option value="">Seleccione...</option>
                        <?php
                        foreach ($datos['tipos_aporte'] as $v) {
                        ?>
                            <option value="<?= $v->id_tipo_aporte ?>"><?= $v->ta_descripcion ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <p id="error-tipos_aporte" class="invalid-feedback">Debes seleccionar al menos un tipo de aporte de evaluación...</p>
                </div>
                <div class="mb-2">
                    <label for="nombre" class="form-label form-control-sm requerido">Nombre:</label>
                    <input type="text" class="form-control text-uppercase form-control-sm" value="" name="nombre" id="nombre" required>
                    <p id="error-nombre" class="invalid-feedback">El nombre del aporte de evaluación debe contener de 4 a 48 caracteres alfabéticos y/o el caracter espacio en blanco.</p>
                </div>
                <div class="mb-2">
                    <label for="abreviatura" class="form-label form-control-sm requerido">Abreviatura:</label>
                    <input type="text" class="form-control text-uppercase form-control-sm" value="" name="abreviatura" id="abreviatura" required>
                    <p id="error-abreviatura" class="invalid-feedback">La abreviatura del aporte de evaluación debe contener de 4 a 8 caracteres alfabéticos.</p>
                </div>
                <!-- <div class="mb-2">
                    <label for="ponderación" class="form-label form-control-sm requerido">Ponderación:</label>
                    <input name="ponderación" id="ponderación" type="number" min="7" max="10" step="0.01" class="form-control form-control-sm" value="" required>
                    <p id="error-ponderación" class="invalid-feedback">La ponderación del aporte de evaluación debe contener de 4 a 256 caracteres alfabéticos y espacios entre palabras.</p>
                </div> -->
                <div class="mb-2">
                    <label for="descripcion" class="form-label form-control-sm requerido">Descripción:</label>
                    <textarea class="form-control text-uppercase form-control-sm" name="descripcion" id="descripcion" rows="2"></textarea>
                    <p id="error-descripcion" class="invalid-feedback">La descripción del aporte de evaluación debe contener de 4 a 256 caracteres alfabéticos y espacios entre palabras.</p>
                </div>
                
                <div class="row">
                    <div class="col-12 text-center">
                        <button id="btn-submit" type="submit" class="btn btn-primary btn-sm rounded-0"><i class="fa fa-save"></i> Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const base_url = "<?php echo RUTA_URL; ?>";
</script>
<script src="<?php echo RUTA_URL; ?>public/assets/js/pages/admin/aportes_evaluacion/create.js"></script>