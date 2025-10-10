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
                    <input type="text" class="form-control" value="" name="nombre" id="nombre" required>
                    <p id="error-nombre" class="invalid-feedback">El nombre del subnivel de educación debe contener de 4 a 64 caracteres alfabéticos y el caracter espacio en blanco.</p>
                </div>
                <div class="mb-3">
                    <label for="abreviatura" class="form-label requerido">Abreviatura:</label>
                    <input type="text" class="form-control" value="" name="abreviatura" id="abreviatura" required>
                    <p id="error-abreviatura" class="invalid-feedback">La abreviatura del subnivel de educación debe contener de 4 a 64 caracteres alfabéticos y el caracter espacio en blanco.</p>
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
                <button id="btn-submit" type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?= RUTA_URL . "subperiodos_evaluacion" ?>" class="btn btn-secondary">Regresar</a>
            </form>
        </div>
    </div>
</div>

<script>
    const base_url = "<?php echo RUTA_URL; ?>";
</script>
<script src="<?php echo RUTA_URL; ?>public/assets/js/pages/admin/subperiodos_evaluacion/create.js"></script>