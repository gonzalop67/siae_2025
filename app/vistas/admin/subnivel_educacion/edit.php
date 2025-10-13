<div class="container-fluid px-4">
    <div class="card mt-2 mb-4">
        <div class="card-header">
            <i class="fa fa-user-gear me-1"></i>
            Editar Subnivel de Educación
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['msg'])) : ?>
                <div class="alert alert-<?= $_SESSION['msg.type'] ?> alert-dismissible fade show" role="alert">
                    <p><i class="icon fa fa-<?= $_SESSION['msg.icon'] ?>"></i> <?= $_SESSION['msg.body'] ?></p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif ?>
            <form id="formulario" action="" method="post">
                <input type="hidden" name="id_nivel_educacion" value="<?= $datos['subnivel']->id_nivel_educacion ?>">
                <div class="mb-3">
                    <label for="nombre" class="form-label requerido">Nombre:</label>
                    <input type="text" class="form-control text-uppercase" value="<?= $datos['subnivel']->nombre ?>" name="nombre" id="nombre" autofocus required>
                    <p id="error-nombre" class="invalid-feedback">El nombre del subnivel de educación debe contener de 4 a 64 caracteres alfabéticos y el caracter espacio en blanco.</p>
                </div>
                <div class="mb-3">
                    <label for="es_bachillerato" class="form-label requerido">¿Es Bachillerato?:</label>
                    <select class="form-select" id="es_bachillerato" name="es_bachillerato">
                        <option value="1" <?= $datos['subnivel']->es_bachillerato == 1 ? 'selected' : '' ?>>Sí</option>
                        <option value="0" <?= $datos['subnivel']->es_bachillerato == 0 ? 'selected' : '' ?>>No</option>
                    </select>
                    <p id="error-es_bachillerato" class="invalid-feedback">Debe seleccionar una opción...</p>
                </div>
                <button id="btn-submit" type="submit" class="btn btn-primary">Actualizar</button>
                <a href="<?= RUTA_URL . "subniveles_educacion" ?>" class="btn btn-secondary">Regresar</a>
            </form>
        </div>
    </div>
</div>

<script>
    const base_url = "<?php echo RUTA_URL; ?>";
</script>
<script src="<?php echo RUTA_URL; ?>public/assets/js/pages/admin/subniveles_educacion/create.js"></script>