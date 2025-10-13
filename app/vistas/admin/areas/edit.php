<div class="container-fluid px-4">
    <div class="card mt-2 mb-4">
        <div class="card-header">
            <i class="fa fa-user-gear me-1"></i>
            Editar Área del Conocimiento
        </div>
        <div class="card-body">
            <form id="formulario" action="" method="post">
                <input type="hidden" name="id_area" value="<?= $datos['area']->id_area ?>">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" class="form-control text-uppercase" value="<?= $datos['area']->ar_nombre ?>" name="nombre" id="nombre" required>
                    <p id="error-nombre" class="invalid-feedback">El nombre del área debe contener de 4 a 64 caracteres alfabéticos y/o el caracter espacio en blanco.</p>
                </div>
                <div class="mb-3">
                    <label for="activo" class="form-label">Activo:</label>
                    <select class="form-select" id="activo" name="activo">
                        <option value="1" <?= $datos['area']->ar_activo == 1 ? 'selected' : '' ?>>Sí</option>
                        <option value="0" <?= $datos['area']->ar_activo == 0 ? 'selected' : '' ?>>No</option>
                    </select>
                    <p id="error-activo" class="invalid-feedback"></p>
                </div>
                <button id="btn-submit" type="submit" class="btn btn-primary">Actualizar</button>
                <a href="<?= RUTA_URL . "areas" ?>" class="btn btn-secondary">Regresar</a>
            </form>
        </div>
    </div>
</div>

<script>
    const base_url = "<?php echo RUTA_URL; ?>";
</script>
<script src="<?php echo RUTA_URL; ?>public/assets/js/pages/admin/areas/create.js"></script>