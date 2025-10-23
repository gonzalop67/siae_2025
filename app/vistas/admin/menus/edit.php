<div class="container-fluid px-4">
    <div class="card mt-2 mb-4">
        <div class="card-header">
            <i class="fa fa-bars me-1"></i>
            <span class="fw-bold">Editar Menú</span>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['msg'])) : ?>
                <div class="alert alert-<?= $_SESSION['msg.type'] ?> alert-dismissible fade show" role="alert">
                    <p><i class="icon fa fa-<?= $_SESSION['msg.icon'] ?>"></i> <?= $_SESSION['msg.body'] ?></p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif ?>
            <form id="formulario" action="" method="post">
                <input type="hidden" name="id_menu" value="<?= $datos['menu']->id_menu ?>">
                <div class="form-group row mb-3">
                    <div class="col-lg-3 text-end">
                        <label for="texto" class="form-label requerido">Texto:</label>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" value="<?= $datos['menu']->mnu_texto ?>" name="texto" id="texto" required>
                        <span id="error-texto" class="invalid-feedback">El texto del menú debe contener de 4 a 64 caracteres alfanuméricos incluyendo el espacio en blanco.</span>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-lg-3 text-end">
                        <label for="enlace" class="form-label requerido">Enlace:</label>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" value="<?= $datos['menu']->mnu_link ?>" name="enlace" id="enlace" required>
                        <span id="error-enlace" class="invalid-feedback">El enlace del menú debe contener de 4 a 64 caracteres alfabéticos y el caracter guión bajo.</span>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-lg-3 text-end">
                        <label for="icono" class="form-label">Icono:</label>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" value="<?= $datos['menu']->mnu_icono ?>" name="icono" id="icono">
                        <span id="error-icono" class="invalid-feedback">El icono del menú debe contener de 4 a 64 caracteres alfabéticos y el caracter guión bajo.</span>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-lg-3 text-end">
                        <label for="publicado" class="form-label requerido">Publicado:</label>
                    </div>
                    <div class="col-lg-8">
                        <select class="form-select" id="publicado" name="publicado">
                            <option value="1" <?= $datos['menu']->mnu_publicado == 1 ? 'selected' : '' ?>>Sí</option>
                            <option value="0" <?= $datos['menu']->mnu_publicado == 0 ? 'selected' : '' ?>>No</option>
                        </select>
                        <p id="error-publicado" class="invalid-feedback"></p>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-lg-3 text-end">
                        <label for="perfil" class="form-label requerido">Perfil:</label>
                    </div>
                    <div class="col-lg-8">
                        <?php foreach ($datos['perfiles'] as $v) : ?>
                            <div class="control">
                                <label class="checkbox">
                                    <input type="checkbox" name="perfiles[]" value="<?= $v->id_perfil ?>" <?= (in_array($v->id_perfil, $datos['perfilesMenu'])) ? 'checked' : '' ?>>
                                    <?= $v->pe_nombre ?>
                                </label>
                            </div>
                        <?php endforeach ?>
                        <p id="error-perfiles" class="invalid-feedback">Debes seleccionar al menos un perfil...</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3"></div>
                    <div class="col-lg-9">
                        <button id="btn-submit" type="submit" class="btn btn-primary">Actualizar</button>
                        <a href="<?= RUTA_URL . "menus" ?>" class="btn btn-secondary">Regresar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const base_url = "<?php echo RUTA_URL; ?>";
</script>
<script src="<?php echo RUTA_URL; ?>public/assets/js/pages/admin/menus/create.js"></script>