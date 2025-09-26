<div class="container-fluid px-4">
    <div class="card mt-2 mb-4">
        <div class="card-header">
            <i class="fa fa-user-gear me-1"></i>
            Editar Perfil
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['msg'])) : ?>
                <div class="alert alert-<?= $_SESSION['msg.type'] ?> alert-dismissible fade show" role="alert">
                    <p><i class="icon fa fa-<?= $_SESSION['msg.icon'] ?>"></i> <?= $_SESSION['msg.body'] ?></p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif ?>
            <form id="formulario" action="" method="post">
                <input type="hidden" name="id_perfil" value="<?= $datos['perfil']->id_perfil ?>">
                <div class="mb-3">
                    <label for="nombre" class="form-label fw-bold">Nombre:</label>
                    <input type="text" class="form-control" value="<?= $datos['perfil']->pe_nombre ?>" name="nombre" id="nombre" autofocus required>
                    <p id="error-nombre" class="invalid-feedback">El nombre del perfil debe contener de 4 a 64 caracteres alfabéticos y el caracter espacio en blanco.</p>
                </div>
                <div class="mb-3">
                    <label for="slug" class="form-label fw-bold">Slug:</label>
                    <input type="text" class="form-control" value="<?= $datos['perfil']->pe_slug ?>" name="slug" id="slug" required>
                    <p id="error-slug" class="invalid-feedback">El slug del perfil debe contener de 4 a 64 caracteres alfabéticos y el caracter guión bajo.</p>
                </div>
                <button id="btn-submit" type="submit" class="btn btn-primary">Actualizar</button>
                <a href="<?= RUTA_URL . "perfiles" ?>" class="btn btn-secondary">Regresar</a>
            </form>
        </div>
    </div>
</div>

<script>
    const base_url = "<?php echo RUTA_URL; ?>";
</script>
<script src="<?php echo RUTA_URL; ?>public/assets/js/pages/admin/perfiles/create.js"></script>