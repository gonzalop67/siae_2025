<div class="container-fluid px-4">
    <div class="card mt-2 mb-4">
        <div class="card-header">
            <i class="far fa-calendar me-1"></i>
            Editar Jornada
        </div>
        <div class="card-body">
            <form id="formulario" action="" method="post">
                <input type="hidden" name="id_jornada" value="<?= $datos['jornada']->id_jornada ?>">
                <div class="mb-3">
                    <label for="nombre" class="form-label requerido">Nombre:</label>
                    <input type="text" class="form-control text-uppercase" value="<?= $datos['jornada']->jo_nombre ?>" name="nombre" id="nombre" required>
                    <p id="error-nombre" class="invalid-feedback">El nombre de la jornada debe contener de 4 a 16 caracteres alfabéticos sin espacios en blanco.</p>
                </div>
                <button id="btn-submit" type="submit" class="btn btn-primary">Actualizar</button>
                <a href="<?= RUTA_URL . "jornadas" ?>" class="btn btn-secondary">Regresar</a>
            </form>
        </div>
    </div>
</div>

<script>
    const base_url = "<?php echo RUTA_URL; ?>";
</script>
<script src="<?php echo RUTA_URL; ?>public/assets/js/pages/admin-ue/jornadas/create.js"></script>