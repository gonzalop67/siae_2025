<div class="container-fluid px-4">
    <div class="card mt-2 mb-4">
        <div class="card-header">
            <i class="fa fa-user-gear me-1"></i>
            Crear Nueva Área del Conocimiento

            <a href="<?= RUTA_URL . "areas" ?>" class="btn btn-success btn-sm rounded-0 float-end"><i class="fa fa-backward"></i> Regresar</a>
        </div>
        <div class="card-body">
            <form id="formulario" action="" method="post">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" class="form-control text-uppercase" value="" name="nombre" id="nombre" required>
                    <p id="error-nombre" class="invalid-feedback">El nombre del área debe contener de 4 a 64 caracteres alfabéticos y/o el caracter espacio en blanco.</p>
                </div>
                <div class="mb-3">
                    <label for="activo" class="form-label">Activo:</label>
                    <select class="form-select" id="activo" name="activo">
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                    <p id="error-activo" class="invalid-feedback"></p>
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
<script src="<?php echo RUTA_URL; ?>public/assets/js/pages/admin/areas/create.js"></script>