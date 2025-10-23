<div class="container-fluid px-4">
    <div class="card mt-2 mb-4">
        <div class="card-header">
            <i class="fa fa-user-gear me-1"></i>
            Editar Modalidad
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['msg'])) : ?>
                <div class="alert alert-<?= $_SESSION['msg.type'] ?> alert-dismissible fade show" role="alert">
                    <p><i class="icon fa fa-<?= $_SESSION['msg.icon'] ?>"></i> <?= $_SESSION['msg.body'] ?></p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif ?>
            <form id="formulario" action="" method="post">
                <input type="hidden" name="id_modalidad" value="<?= $datos['modalidad']->id_modalidad ?>">
                <div class="mb-3">
                    <label for="nombre" class="form-label fw-bold">Nombre:</label>
                    <input type="text" class="form-control text-uppercase" value="<?= $datos['modalidad']->mo_nombre ?>" name="nombre" id="nombre" required>
                    <p id="error-nombre" class="invalid-feedback">El nombre de la modalidad debe contener de 4 a 64 caracteres alfabéticos y/o el caracter espacio en blanco.</p>
                </div>
                <div class="mb-3">
                    <label for="activo" class="form-label fw-bold">Activo:</label>
                    <select class="form-select" id="activo" name="activo">
                        <option value="1" <?= $datos['modalidad']->mo_activo == 1 ? 'selected' : '' ?>>Sí</option>
                        <option value="0" <?= $datos['modalidad']->mo_activo == 0 ? 'selected' : '' ?>>No</option>
                    </select>
                    <p id="error-activo" class="invalid-feedback"></p>
                </div>
                <button id="btn-submit" type="submit" class="btn btn-primary">Actualizar</button>
                <a href="<?= RUTA_URL . "modalidades" ?>" class="btn btn-secondary">Regresar</a>
            </form>
        </div>
    </div>
</div>

<script>
    const base_url = "<?php echo RUTA_URL; ?>";
</script>
<script src="<?php echo RUTA_URL; ?>public/assets/js/pages/admin/modalidades/create.js"></script>