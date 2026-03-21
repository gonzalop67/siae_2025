<div class="container-fluid px-4">
    <div class="card mt-2 mb-4">
        <div class="card-header">
            <i class="fa fa-user-gear me-1"></i>
            Crear Nuevo Subnivel de Educación
        </div>
        <div class="card-body">
            <?php
            include RUTA_APP . "/vistas/layouts/includes/mensaje.php";
            ?>
            <form id="formulario" action="" method="post">
                <div class="mb-3">
                    <label for="nivel_id" class="form-label requerido">Nivel de Educación:</label>
                    <select class="form-select" id="nivel_id" name="nivel_id">
                        <option value="">Seleccione...</option>
                        <?php
                        foreach ($datos['niveles_educacion'] as $nivel_educacion) {
                        ?>
                            <option value="<?= $nivel_educacion->id ?>"><?= $nivel_educacion->nombre ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <p id="error-nivel_id" class="invalid-feedback">Debe seleccionar una opción...</p>
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label requerido">Nombre:</label>
                    <input type="text" class="form-control" value="" name="nombre" id="nombre" required>
                    <p id="error-nombre" class="invalid-feedback">El nombre del subnivel de educación debe contener de 4 a 64 caracteres alfabéticos y el caracter espacio en blanco.</p>
                </div>
                <div class="mb-3">
                    <label for="slug" class="form-label">Slug:</label>
                    <input type="text" class="form-control" value="" name="slug" id="slug" required>
                    <p id="error-slug" class="invalid-feedback">El slug del subnivel de educación debe contener de 4 a 64 caracteres alfabéticos y el caracter guión medio.</p>
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