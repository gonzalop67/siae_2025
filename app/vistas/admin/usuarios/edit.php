<div class="container-fluid px-4">
    <div class="card mt-2 mb-4">
        <div class="card-header fw-bold">
            <i class="fa fa-user-edit me-1"></i>
            Editar Usuario
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['msg'])) : ?>
                <div class="alert alert-<?= $_SESSION['msg.type'] ?> alert-dismissible fade show" role="alert">
                    <p><i class="icon fa fa-<?= $_SESSION['msg.icon'] ?>"></i> <?= $_SESSION['msg.body'] ?></p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif ?>
            <form id="formulario" action="" enctype="multipart/form-data" method="post">
                <input type="hidden" name="id_usuario" value="<?= $datos['usuario']->id_usuario ?>">
                <div class="mb-3">
                    <label for="abreviatura" class="form-label">Título Abreviatura:</label>
                    <input type="text" class="form-control" value="<?= $datos['usuario']->us_titulo ?>" name="abreviatura" id="abreviatura" placeholder="Abreviatura del Título. Ejemplo: Ing." autofocus>
                    <p id="error-abreviatura" class="invalid-feedback">La abreviatura del título tiene que ser de 4 a 7 caracteres y solo puede contener caracteres alfabéticos y el caracter punto.</p>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Título Descripcion:</label>
                    <input type="text" class="form-control" value="<?= $datos['usuario']->us_titulo_descripcion ?>" name="descripcion" id="descripcion" placeholder="Descripción del Título">
                    <p id="error-descripcion" class="invalid-feedback">La descripción del título tiene que ser de 4 a 64 caracteres y solo puede contener caracteres alfabéticos, puede contener acentos.</p>
                </div>
                <div class="mb-3">
                    <label for="apellidos" class="form-label">Apellidos:</label>
                    <input type="text" class="form-control" value="<?= $datos['usuario']->us_apellidos ?>" name="apellidos" id="apellidos" placeholder="Apellidos del Usuario">
                    <p id="error-apellidos" class="invalid-feedback">Los apellidos del usuario deben contener de 3 a 32 caracteres alfabéticos incluyendo acentos.</p>
                </div>
                <div class="mb-3">
                    <label for="nombres" class="form-label">Nombres:</label>
                    <input type="text" class="form-control" value="<?= $datos['usuario']->us_nombres ?>" name="nombres" id="nombres" placeholder="Nombres del Usuario">
                    <p id="error-nombres" class="invalid-feedback">Los nombres del usuario deben contener de 3 a 32 caracteres alfabéticos incluyendo acentos.</p>
                </div>
                <div class="mb-3">
                    <label for="nombre_corto" class="form-label">Nombre Corto:</label>
                    <input type="text" class="form-control" value="<?= $datos['usuario']->us_shortname ?>" name="nombre_corto" id="nombre_corto" placeholder="Nombre Corto del Usuario">
                    <p id="error-nombre_corto" class="invalid-feedback">El campo Nombre Corto debe contener de 3 a 32 caracteres alfabéticos incluyendo acentos.</p>
                </div>
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario:</label>
                    <input type="text" class="form-control" value="<?= $datos['usuario']->us_login ?>" name="usuario" id="usuario" placeholder="Nombre de Usuario">
                    <p id="error-usuario" class="invalid-feedback">El usuario tiene que ser de 4 a 16 caracteres y solo puede contener numeros, letras y guion bajo.</p>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <?php $clave = Encrypter::decrypt($datos['usuario']->us_password) ?>
                    <input type="text" class="form-control" value="<?= $clave ?>" name="password" id="password" placeholder="Clave del Usuario">
                    <p id="error-password" class="invalid-feedback">La contraseña debe contener un dígito del 1 al 9, una letra minúscula, una letra mayúscula, un carácter especial ["$","@","!","%","*","?","&","+"], ningún espacio y debe tener entre 8 y 15 caracteres.</p>
                </div>
                <div class="mb-3">
                    <label for="genero" class="form-label">Género:</label>
                    <select class="form-select" id="genero" name="genero">
                        <option value="F" <?= $datos['usuario']->us_genero == 'F' ? 'selected' : '' ?>>Femenino</option>
                        <option value="M" <?= $datos['usuario']->us_genero == 'M' ? 'selected' : '' ?>>Masculino</option>
                    </select>
                    <p id="error-genero" class="invalid-feedback"></p>
                </div>
                <div class="mb-3">
                    <label for="activo" class="form-label">Activo:</label>
                    <select class="form-select" id="activo" name="activo">
                        <option value="1" <?= $datos['usuario']->us_activo == '1' ? 'selected' : '' ?>>Sí</option>
                        <option value="0" <?= $datos['usuario']->us_activo == '0' ? 'selected' : '' ?>>No</option>
                    </select>
                    <p id="error-activo" class="invalid-feedback"></p>
                </div>
                <div class="mb-3">
                    <label for="perfiles" class="form-label">Perfil:</label>
                    <?php foreach ($datos['perfiles'] as $v) : ?>
                        <div class="control">
                            <label class="checkbox">
                                <input type="checkbox" name="perfiles[]" value="<?= $v->id_perfil ?>" <?= (in_array($v->id_perfil, $datos['perfilesUsuario'])) ? 'checked' : '' ?>>
                                <?= $v->pe_nombre ?>
                            </label>
                        </div>
                    <?php endforeach ?>
                    <p id="error-perfiles" class="invalid-feedback">Debes seleccionar al menos un perfil...</p>
                </div>
                <div id="img_upload">
                    <div class="mb-3">
                        <label for="avatar" class="form-label">Avatar</label>
                        <div id="img_div">
                            <img id="avatar" src="<?php echo RUTA_URL . 'public/uploads/' . $datos['usuario']->us_foto ?>" name="avatar" class="img-thumbnail" width="75">
                        </div>
                    </div>
                    <div class="mb-3">
                        <!-- <label for="foto" class="form-label"></label> -->
                        <input type="file" name="foto" id="foto">
                        <p id="error-foto" class="invalid-feedback"></p>
                    </div>
                </div>
                <button id="btn-submit" type="submit" class="btn btn-primary">Actualizar</button>
                <a href="<?= RUTA_URL . "usuarios" ?>" class="btn btn-secondary">Regresar</a>
            </form>
        </div>
    </div>
</div>

<script>
    const base_url = "<?php echo RUTA_URL; ?>";
</script>
<script src="<?php echo RUTA_URL; ?>public/assets/js/pages/admin/usuarios/create.js"></script>