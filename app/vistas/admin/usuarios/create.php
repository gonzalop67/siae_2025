<div class="container-fluid px-4">
    <div class="card mt-2 mb-4">
        <div class="card-header">
            <i class="fa fa-user-plus me-1"></i>
            <span class="fw-bold">Crear Nuevo Usuario</span>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['msg'])) : ?>
                <div class="alert alert-<?= $_SESSION['msg.type'] ?> alert-dismissible fade show" role="alert">
                    <p><i class="icon fa fa-<?= $_SESSION['msg.icon'] ?>"></i> <?= $_SESSION['msg.body'] ?></p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif ?>
            <form id="formulario" action="" enctype="multipart/form-data" method="post">
                <div class="mb-3">
                    <label for="abreviatura" class="form-label">Título Abreviatura:</label>
                    <input type="text" class="form-control" value="" name="abreviatura" id="abreviatura" placeholder="Abreviatura del Título. Ejemplo: Ing.">
                    <p id="error-abreviatura" class="invalid-feedback">La abreviatura del título tiene que ser de 4 a 7 caracteres y solo puede contener caracteres alfabéticos y el caracter punto.</p>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Título Descripcion:</label>
                    <input type="text" class="form-control" value="" name="descripcion" id="descripcion" placeholder="Descripción del Título">
                    <p id="error-descripcion" class="invalid-feedback">La descripción del título tiene que ser de 4 a 64 caracteres y solo puede contener caracteres alfabéticos, puede contener acentos.</p>
                </div>
                <div class="mb-3">
                    <label for="apellidos" class="form-label">Apellidos:</label>
                    <input type="text" class="form-control" value="" name="apellidos" id="apellidos" placeholder="Apellidos del Usuario">
                    <p id="error-apellidos" class="invalid-feedback">Los apellidos del usuario deben contener de 3 a 32 caracteres alfabéticos incluyendo acentos.</p>
                </div>
                <div class="mb-3">
                    <label for="nombres" class="form-label">Nombres:</label>
                    <input type="text" class="form-control" value="" name="nombres" id="nombres" placeholder="Nombres del Usuario">
                    <p id="error-nombres" class="invalid-feedback">Los nombres del usuario deben contener de 3 a 32 caracteres alfabéticos incluyendo acentos.</p>
                </div>
                <div class="mb-3">
                    <label for="nombre_corto" class="form-label">Nombre Corto:</label>
                    <input type="text" class="form-control" value="" name="nombre_corto" id="nombre_corto" placeholder="Nombre Corto del Usuario">
                    <p id="error-nombre_corto" class="invalid-feedback">El campo Nombre Corto debe contener de 3 a 32 caracteres alfabéticos incluyendo acentos.</p>
                </div>
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario:</label>
                    <input type="text" class="form-control" value="" name="usuario" id="usuario" placeholder="Nombre de Usuario">
                    <p id="error-usuario" class="invalid-feedback">El usuario tiene que ser de 4 a 16 caracteres y solo puede contener numeros, letras y guion bajo.</p>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="text" class="form-control" value="" name="password" id="password" placeholder="Clave del Usuario">
                    <p id="error-password" class="invalid-feedback">La contraseña debe contener un dígito del 1 al 9, una letra minúscula, una letra mayúscula, un carácter especial ["$","@","!","%","*","?","&","+"], ningún espacio y debe tener entre 8 y 15 caracteres.</p>
                </div>
                <div class="mb-3">
                    <label for="genero" class="form-label">Género:</label>
                    <select class="form-select" id="genero" name="genero">
                        <option value="F">Femenino</option>
                        <option value="M">Masculino</option>
                    </select>
                    <p id="error-genero" class="invalid-feedback"></p>
                </div>
                <div class="mb-3">
                    <label for="activo" class="form-label">Activo:</label>
                    <select class="form-select" id="activo" name="activo">
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                    <p id="error-activo" class="invalid-feedback"></p>
                </div>
                <div class="mb-3">
                    <label for="perfiles" class="form-label">Perfil:</label>
                    <?php foreach ($datos['perfiles'] as $v) : ?>
                        <div class="control">
                            <label class="checkbox">
                                <input type="checkbox" name="perfiles[]" value="<?= $v->id_perfil ?>">
                                <?= $v->pe_nombre ?>
                            </label>
                        </div>
                    <?php endforeach ?>
                    <p id="error-perfiles" class="invalid-feedback">Debes seleccionar al menos un perfil...</p>
                </div>
                <div id="img_upload">
                    <div class="mb-3">
                        <label for="avatar" class="form-label">Avatar</label>
                        <div id="img_div" class="hide">
                            <img id="avatar" name="avatar" class="img-thumbnail d-none" width="75">
                        </div>
                    </div>
                    <div class="mb-3">
                        <!-- <label for="foto" class="form-label"></label> -->
                        <input type="file" name="foto" id="foto">
                        <p id="error-foto" class="invalid-feedback">Debes seleccionar un archivo de imagen</p>
                    </div>
                </div>
                <button id="btn-submit" type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?= RUTA_URL . "usuarios" ?>" class="btn btn-secondary">Regresar</a>
            </form>
        </div>
    </div>
</div>

<script>
    const base_url = "<?php echo RUTA_URL; ?>";
</script>
<script src="<?php echo RUTA_URL; ?>public/assets/js/pages/admin/usuarios/create.js"></script>