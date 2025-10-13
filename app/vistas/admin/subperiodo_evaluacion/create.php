<div class="container-fluid px-4">
    <div class="card mt-2 mb-4">
        <div class="card-header">
            <i class="fa fa-user-gear me-1"></i>
            Crear Nuevo Subperiodo de Evaluación
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['msg'])) : ?>
                <div class="alert alert-<?= $_SESSION['msg.type'] ?> alert-dismissible fade show" role="alert">
                    <p><i class="icon fa fa-<?= $_SESSION['msg.icon'] ?>"></i> <?= $_SESSION['msg.body'] ?></p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif ?>
            <form id="formulario" action="" method="post">
                <div class="mb-3">
                    <label for="nombre" class="form-label requerido">Nombre:</label>
                    <input type="text" class="form-control text-uppercase" value="" name="nombre" id="nombre" required>
                    <p id="error-nombre" class="invalid-feedback">El nombre del subperiodo de evaluación debe contener de 4 a 64 caracteres alfabéticos y el caracter espacio en blanco.</p>
                </div>
                <div class="mb-3">
                    <label for="abreviatura" class="form-label requerido">Abreviatura:</label>
                    <input type="text" class="form-control text-uppercase" value="" name="abreviatura" id="abreviatura" required>
                    <p id="error-abreviatura" class="invalid-feedback">La abreviatura del subperiodo de evaluación debe contener de 4 a 64 caracteres alfabéticos y el caracter espacio en blanco.</p>
                </div>
                <div class="mb-3">
                    <label for="tipo_periodo" class="form-label requerido">Tipo de Periodo:</label>
                    <select class="form-select" id="tipo_periodo" name="tipo_periodo">
                        <option value="">Seleccione...</option>
                        <?php
                        foreach ($datos['tipos_periodo'] as $tipo_periodo) {
                        ?>
                            <option value="<?= $tipo_periodo->id_tipo_periodo ?>"><?= $tipo_periodo->tp_descripcion ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <p id="error-tipo_periodo" class="invalid-feedback">Debe seleccionar una opción...</p>
                </div>
                <!-- <div id="div_rango" class="mb-3" style="display: none;">
                    <fieldset>
                        <legend>Rango de obtención del examen:</legend>
                        <div class="row" class="mt-2">
                            <div class="col-lg-3 text-end">
                                <label for="nota_desde" class="form-label">Nota desde:</label>
                            </div>
                            <div class="col-lg-9 mb-3">
                                <input type="number" min="0.01" step="0.01" class="form-control" id="nota_desde" name="nota_desde" value="0.01" onfocus="sel_texto(this)" onkeypress="return permite(event,'num')">
                                <p id="error-nota_desde" class="invalid-feedback">Debe ingresar un número real entre 0.01 y 1</p>
                            </div>
                        </div>
                        <div class="row" class="mt-2">
                            <div class="col-lg-3 text-end">
                                <label for="nota_hasta" class="form-label">Nota hasta:</label>
                            </div>
                            <div class="col-lg-9">
                                <input type="number" min="0.01" step="0.01" class="form-control" id="nota_hasta" name="nota_hasta" value="0.01" onfocus="sel_texto(this)" onkeypress="return permite(event,'num')">
                                <p id="error-nota_hasta" class="invalid-feedback">Debe ingresar un número real entre 0.01 y 1</p>
                            </div>
                        </div>
                    </fieldset>
                </div> -->
                <button id="btn-submit" type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?= RUTA_URL . "subperiodos_evaluacion" ?>" class="btn btn-secondary">Regresar</a>
            </form>
        </div>
    </div>
</div>

<script>
    const base_url = "<?php echo RUTA_URL; ?>";
</script>
<script src="<?php echo RUTA_URL; ?>public/assets/js/funciones.js"></script>
<script src="<?php echo RUTA_URL; ?>public/assets/js/keypress.js"></script>
<script src="<?php echo RUTA_URL; ?>public/assets/js/pages/admin/subperiodos_evaluacion/create.js"></script>