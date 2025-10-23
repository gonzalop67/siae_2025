<div class="container-fluid px-4">
    <div class="card mt-2 mb-4">
        <div class="card-header">
            <i class="fa fa-user-gear me-1"></i>
            Crear Nuevo Subnivel de Educación
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
                    <p id="error-nombre" class="invalid-feedback">El nombre del subnivel de educación debe contener de 4 a 64 caracteres alfabéticos y el caracter espacio en blanco.</p>
                </div>
                <div class="mb-3">
                    <label for="es_bachillerato" class="form-label requerido">¿Es Bachillerato?:</label>
                    <select class="form-select" id="es_bachillerato" name="es_bachillerato">
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                    <p id="error-es_bachillerato" class="invalid-feedback">Debe seleccionar una opción...</p>
                </div>
                <button id="btn-submit" type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?= RUTA_URL . "subniveles_educacion" ?>" class="btn btn-secondary">Regresar</a>
            </form>
        </div>
    </div>
</div>

<script>
    const base_url = "<?php echo RUTA_URL; ?>";
</script>
<script src="<?php echo RUTA_URL; ?>public/assets/js/pages/admin/subniveles_educacion/create.js"></script>