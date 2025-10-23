<div class="container-fluid px-4">
    <div class="card mb-4 mt-2">
        <div class="card-header">
            <i class="fas fa-building-columns me-1"></i>
            Datos de la Institución Educativa

            <a href="<?= RUTA_URL . "instituciones" ?>" class="btn btn-block btn-primary btn-sm float-end">
                <i class="fa-solid fa-backward-fast"></i> Volver al listado
            </a>
        </div>
        <div class="card-body">

            <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="alert alert-<?= isset($_SESSION['tipo']) ? $_SESSION['tipo'] : 'danger' ?> alert-dismissible fade show" role="alert">
                    <p><i class="icon fa fa-<?= isset($_SESSION['icono']) ? $_SESSION['icono'] : 'ban' ?>"></i> <span><?php echo isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : '' ?></span></p>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>

            <?php if (isset($_SESSION['mensaje'])) unset($_SESSION['mensaje']) ?>
            <?php if (isset($_SESSION['tipo'])) unset($_SESSION['tipo']) ?>
            <?php if (isset($_SESSION['icono'])) unset($_SESSION['icono']) ?>

            <form id="formulario" action="<?= RUTA_URL . "instituciones/update/" ?>" enctype="multipart/form-data" method="post">
                <input type="hidden" name="id_institucion" value="<?= $datos['institucion']->id_institucion ?>">
                <div class="row mb-2">
                    <div class="col-12">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control text-uppercase" value="<?= $datos['institucion']->in_nombre ?>" name="nombre" id="nombre" required>
                        <p id="error-nombre" class="invalid-feedback">El nombre de la Institución Educativa tiene que contener de 4 a 64 caracteres y solo puede contener caracteres alfabéticos.</p>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-12">
                        <label for="direccion" class="form-label">Dirección:</label>
                        <input type="text" class="form-control" value="<?= $datos['institucion']->in_direccion ?>" name="direccion" id="direccion" required>
                        <p id="error-direccion" class="invalid-feedback">La dirección de la Institución Educativa tiene que contener de 4 a 64 caracteres y puede contener caracteres alfanuméricos y el punto.</p>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-12">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" value="<?= $datos['institucion']->in_email ?>" name="email" id="email" required>
                        <p id="error-email" class="invalid-feedback">El Email de la Institución Educativa tiene que contener de 4 a 64 caracteres y debe contener un formato de correo electrónico válido.</p>
                    </div>
                </div>
                <div id="administrador">
                    <div class="mb-3">
                        <label class="form-label">Administrador</label>
                        <?php if ($datos['administrador']->us_foto != '') : ?>
                            <div id="img_admin">
                                <img src="<?= RUTA_URL . "public/uploads/" . $datos['administrador']->us_foto; ?>" id="avatar_admin" name="avatar_admin" class="img-thumbnail" width="75">
                            </div>
                        <?php else : ?>
                            <div id="img_admin" class="hide">
                                <img src="<?= RUTA_URL . "public/uploads/no-disponible.jpg"; ?>" id="avatar_admin" name="avatar_admin" class="img-thumbnail" width="75">
                            </div>
                        <?php endif ?>
                        <input type="hidden" name="id_usuario_admin" value="<?= $datos['administrador']->id_usuario ?>">
                        <label class="form-label mt-2">Seleccione Administrador de Institución Educativa</label>
                        <select class="form-select" id="admin_id" name="admin_id">
                            <?php
                            for ($i = 0; $i < count($datos['admin_list']); $i++) {
                            ?>
                                <option value="<?= $datos['admin_list'][$i]->id_usuario ?>" <?= $datos['admin_list'][$i]->id_usuario == $datos['institucion']->admin_id ? 'selected' : '' ?>><?= $datos['admin_list'][$i]->us_shortname ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6 col-sm-12 col-md-6">
                        <label for="telefono" class="form-label">Teléfono:</label>
                        <input type="text" class="form-control" value="<?= $datos['institucion']->in_telefono ?>" name="telefono" id="telefono" required>
                        <p id="error-telefono" class="invalid-feedback">El campo Teléfono es obligatorio.</p>
                    </div>
                    <div class="col-6 col-sm-12 col-md-6">
                        <label for="regimen" class="form-label">Régimen:</label>
                        <input type="text" class="form-control" value="<?= $datos['institucion']->in_regimen ?>" name="regimen" id="regimen" required>
                        <p id="error-regimen" class="invalid-feedback">El campo Régimen debe contener de 4 a 16 caracteres alfabéticos.</p>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6 col-sm-12 col-md-6">
                        <label for="rector" class="form-label">Rector:</label>
                        <input type="text" class="form-control" value="<?= $datos['institucion']->in_nom_rector ?>" name="rector" id="rector" required>
                        <p id="error-rector" class="invalid-feedback">El nombre del Rector debe contener de 4 a 64 caracteres alfabéticos.</p>
                    </div>
                    <div class="col-6 col-sm-12 col-md-6">
                        <label for="rector_genero" class="form-label">Género:</label>
                        <select class="form-select" id="rector_genero" name="rector_genero">
                            <option value="F" <?= $datos['institucion']->in_genero_rector == "F" ? 'selected' : '' ?>>Femenino</option>
                            <option value="M" <?= $datos['institucion']->in_genero_rector == "M" ? 'selected' : '' ?>>Masculino</option>
                        </select>
                        <p class="invalid-feedback"></p>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6 col-sm-12 col-md-6">
                        <label for="vicerrector" class="form-label">Vicerrector:</label>
                        <input type="text" class="form-control" value="<?= $datos['institucion']->in_nom_vicerrector ?>" name="vicerrector" id="vicerrector" required>
                        <p id="error-vicerrector" class="invalid-feedback">El nombre del Vicerrector debe contener de 4 a 64 caracteres alfabéticos.</p>
                    </div>
                    <div class="col-6 col-sm-12 col-md-6">
                        <label for="vicerrector_genero" class="form-label">Género:</label>
                        <select class="form-select" id="vicerrector_genero" name="vicerrector_genero">
                            <option value="F" <?= $datos['institucion']->in_genero_rector == "F" ? 'selected' : '' ?>>Femenino</option>
                            <option value="M" <?= $datos['institucion']->in_genero_rector == "M" ? 'selected' : '' ?>>Masculino</option>
                        </select>
                        <p class="invalid-feedback"></p>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6 col-sm-12 col-md-6">
                        <label for="secretario" class="form-label">Secretario:</label>
                        <input type="text" class="form-control" value="<?= $datos['institucion']->in_nom_secretario ?>" name="secretario" id="secretario" required>
                        <p id="error-secretario" class="invalid-feedback">El nombre del Secretario debe contener de 4 a 64 caracteres alfabéticos.</p>
                    </div>
                    <div class="col-6 col-sm-12 col-md-6">
                        <label for="secretario_genero" class="form-label">Género:</label>
                        <select class="form-select" id="secretario_genero" name="secretario_genero">
                            <option value="F" <?= $datos['institucion']->in_genero_secretario == "F" ? 'selected' : '' ?>>Femenino</option>
                            <option value="M" <?= $datos['institucion']->in_genero_secretario == "M" ? 'selected' : '' ?>>Masculino</option>
                        </select>
                        <p class="invalid-feedback"></p>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6 col-sm-12 col-md-6">
                        <label for="url" class="form-label">URL:</label>
                        <input type="text" class="form-control" value="<?= $datos['institucion']->in_url ?>" name="url" id="url" required>
                        <p id="error-url" class="invalid-feedback">El campo URL debe contener una dirección URL válida</p>
                    </div>
                    <div class="col-6 col-sm-12 col-md-6">
                        <label for="amie" class="form-label">AMIE:</label>
                        <input type="text" class="form-control" value="<?= $datos['institucion']->in_amie ?>" name="amie" id="amie" required>
                        <p id="error-amie" class="invalid-feedback">El campo AMIE debe contener un código AMIE válido</p>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6 col-sm-12 col-md-6">
                        <label for="ciudad" class="form-label">Ciudad:</label>
                        <input type="text" class="form-control" value="<?= $datos['institucion']->in_ciudad ?>" name="ciudad" id="ciudad" required>
                        <p id="error-ciudad" class="invalid-feedback">El campo ciudad debe contener un nombre de ciudad válido</p>
                    </div>
                    <div class="col-6 col-sm-12 col-md-6">
                        <label for="copiar_y_pegar" class="form-label">Copy & Paste:</label><br>
                        <input type="checkbox" id="copiar_y_pegar" name="copiar_y_pegar" <?php echo ($datos['institucion']->in_copiar_y_pegar == 1) ? "checked" : "" ?> onclick="actualizar_estado_copiar_y_pegar(this)">
                    </div>
                </div>
                <div id="img_upload">
                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo</label>
                        <?php if ($datos['institucion']->in_logo != '') : ?>
                            <div id="img_div">
                                <img src="<?= RUTA_URL . "public/uploads/" . $datos['institucion']->in_logo; ?>" id="avatar" name="avatar" class="img-thumbnail" width="75">
                            </div>
                        <?php else : ?>
                            <div id="img_div" class="hide">
                                <img id="avatar" name="avatar" class="img-thumbnail" width="75">
                            </div>
                        <?php endif ?>
                        <input type="hidden" name="imagen_institucion_oculta" value="<?= $datos['institucion']->in_logo ?>" />
                    </div>
                    <div class="mb-3">
                        <input type="file" name="logo" id="logo">
                        <p id="erro-logo" class="invalid-feedback"></p>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <button id="btn-submit" type="submit" class="btn btn-primary">Actualizar los datos de la institución</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const base_url = "<?php echo RUTA_URL; ?>";
</script>
<script src="<?php echo RUTA_URL; ?>public/assets/js/pages/admin/instituciones/create.js"></script>