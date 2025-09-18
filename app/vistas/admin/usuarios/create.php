<div class="container-fluid px-4">
    <div class="card mt-2 mb-4">
        <div class="card-header">
            <i class="fa fa-graduation-cap me-1"></i>
            Crear Nuevo Usuario
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['msg'])) : ?>
                <div class="alert alert-<?= $_SESSION['msg.type'] ?> alert-dismissible fade show" role="alert">
                    <p><i class="icon fa fa-<?= $_SESSION['msg.icon'] ?>"></i> <?= $_SESSION['msg.body'] ?></p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif ?>
            <form id="frmCreate" action="" enctype="multipart/form-data" method="post">
                <div class="mb-3">
                    <label for="abreviatura" class="form-label">Título Abreviatura:</label>
                    <input type="text" class="form-control" value="" name="abreviatura" id="abreviatura" placeholder="Abreviatura del Título" autofocus>
                    <p id="error-abreviatura" class="invalid-feedback"></p>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Título Descripcion:</label>
                    <input type="text" class="form-control" value="" name="descripcion" id="descripcion" placeholder="Descripción del Título">
                    <p id="error-descripcion" class="invalid-feedback"></p>
                </div>
                <div class="mb-3">
                    <label for="apellidos" class="form-label">Apellidos:</label>
                    <input type="text" class="form-control" value="" name="apellidos" id="apellidos" placeholder="Apellidos del Usuario">
                    <p id="error-apellidos" class="invalid-feedback"></p>
                </div>
                <div class="mb-3">
                    <label for="nombres" class="form-label">Nombres:</label>
                    <input type="text" class="form-control" value="" name="nombres" id="nombres" placeholder="Nombres del Usuario">
                    <p id="error-nombres" class="invalid-feedback"></p>
                </div>
                <div class="mb-3">
                    <label for="nombre_corto" class="form-label">Nombre Corto:</label>
                    <input type="text" class="form-control" value="" name="nombre_corto" id="nombre_corto" placeholder="Nombre Corto del Usuario">
                    <p id="error-nombre_corto" class="invalid-feedback"></p>
                </div>
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario:</label>
                    <input type="text" class="form-control" value="" name="usuario" id="usuario" placeholder="Nombre de Usuario">
                    <p id="error-usuario" class="invalid-feedback"></p>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="text" class="form-control" value="" name="password" id="password" placeholder="Clave del Usuario">
                    <p id="error-password" class="invalid-feedback"></p>
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
                    <p id="error-perfiles" class="invalid-feedback"></p>
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
                        <p id="error-foto" class="invalid-feedback"></p>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" onclick="frmCreate(event);">Guardar</button>
                <a href="<?= RUTA_URL . "usuarios" ?>" class="btn btn-secondary">Regresar</a>
            </form>
        </div>
    </div>
</div>

<script>
    const base_url = "<?php echo RUTA_URL; ?>";

    function frmCreate(e) {
        e.preventDefault();
        alert('Creando nuevo usuario...');
    }
</script>