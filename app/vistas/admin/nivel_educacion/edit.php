<div class="container-fluid px-4">
    <div class="card mt-2 mb-4">
        <div class="card-header">
            <i class="fa fa-user-gear me-1"></i>
            Editar Nivel de Educación
        </div>
        <div class="card-body">
            <?php
            include RUTA_APP . "/vistas/layouts/includes/mensaje.php";
            ?>
            <form id="formulario" action="" method="post">
                <input type="hidden" name="id_nivel_educacion" id="id_nivel_educacion" value="<?php echo $datos['nivel_educacion']->id ?>">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $datos['nivel_educacion']->nombre ?>" required>
                    <p id="error-nombre" class="invalid-feedback">El nombre del nivel de educacion debe contener de 4 a 64 caracteres alfabéticos y el caracter espacio en blanco.</p>
                </div>
                <div class="mb-3">
                    <label for="slug" class="form-label">Slug:</label>
                    <input type="text" class="form-control" name="slug" id="slug" value="<?php echo $datos['nivel_educacion']->slug ?>" required>
                    <p id="error-slug" class="invalid-feedback">El slug del nivel de educacion debe contener de 4 a 64 caracteres alfabéticos y el caracter guión medio.</p>
                </div>
                <button id="btn-submit" type="submit" class="btn btn-primary">Actualizar</button>
                <a href="<?= RUTA_URL . "niveles_educacion" ?>" class="btn btn-secondary">Regresar</a>
            </form>
        </div>
    </div>
</div>

<script>
    const base_url = "<?php echo RUTA_URL; ?>";
</script>
<script src="<?php echo RUTA_URL; ?>public/assets/js/pages/admin/niveles_educacion/create.js"></script>