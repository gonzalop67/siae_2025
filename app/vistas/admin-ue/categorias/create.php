<div class="container-fluid px-4">
    <div class="card mt-2 mb-4">
        <div class="card-header">
            <i class="fa fa-user-gear me-1"></i>
            Crear Nueva Categoría de Especialidad
        </div>
        <div class="card-body">
            <form id="formulario" action="" method="post">
                <div class="mb-3">
                    <label for="nombre" class="form-label requerido">Nombre:</label>
                    <input type="text" class="form-control" value="" name="nombre" id="nombre" required>
                    <p id="error-nombre" class="invalid-feedback">El nombre de la categoría debe contener de 4 a 45 caracteres alfabéticos y/o el caracter espacio en blanco.</p>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button id="btn-submit" type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Guardar</button>
                        <a href="<?= RUTA_URL . "categorias" ?>" class="btn btn-success btn-sm"><i class="fa fa-backward"></i> Regresar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const base_url = "<?php echo RUTA_URL; ?>";
</script>
<script src="<?php echo RUTA_URL; ?>public/assets/js/pages/admin-ue/categorias/create.js"></script>