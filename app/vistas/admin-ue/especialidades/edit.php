<div class="container-fluid px-4">
    <div class="card mt-2 mb-4">
        <div class="card-header">
            <i class="fa fa-graduation-cap me-1"></i>
            Editar Especialidad
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['msg'])) : ?>
                <div class="alert alert-<?= $_SESSION['msg.type'] ?> alert-dismissible fade show" role="alert">
                    <p><i class="icon fa fa-<?= $_SESSION['msg.icon'] ?>"></i> <?= $_SESSION['msg.body'] ?></p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif ?>
            <form id="formulario" action="" method="post">
                <input type="hidden" name="id_especialidad" value="<?= $datos['especialidad']->id_especialidad ?>">
                <div class="mb-3">
                    <label for="figura" class="form-label requerido">Figura Profesional:</label>
                    <input type="text" class="form-control text-uppercase" value="<?= $datos['especialidad']->es_figura ?>" name="figura" id="figura" required>
                    <p id="error-figura" class="invalid-feedback">La figura profesional de la especialidad debe contener de 4 a 50 caracteres alfabéticos y el caracter espacio en blanco.</p>
                </div>
                <div class="mb-3">
                    <label for="abreviatura" class="form-label requerido">Abreviatura:</label>
                    <input type="text" class="form-control text-uppercase" value="<?= $datos['especialidad']->es_abreviatura ?>" name="abreviatura" id="abreviatura" required>
                    <p id="error-abreviatura" class="invalid-feedback">La abreviatura de la especialidad debe contener de 3 a 15 caracteres alfabéticos y el caracter espacio en blanco.</p>
                </div>
                <div class="mb-3">
                    <label for="categoria" class="form-label requerido">Categoría:</label>
                    <select class="form-select" name="categoria" id="categoria" required>
                        <option value="">Seleccione...</option>
                        <?php
                        foreach ($datos['categorias'] as $categoria) {
                        ?>
                            <option value="<?= $categoria->id_categoria ?>" <?= $categoria->id_categoria == $datos['especialidad']->categoria_id ? 'selected' : '' ?>><?= $categoria->nombre ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <p id="error-categoria" class="invalid-feedback">Debe seleccionar una categoría.</p>
                </div>
                <div class="mb-3">
                    <label for="subnivel" class="form-label requerido">Subnivel de Educación:</label>
                    <select class="form-select" name="subnivel" id="subnivel" required>
                        <option value="">Seleccione...</option>
                        <?php
                        foreach ($datos['subniveles'] as $subnivel) {
                        ?>
                            <option value="<?= $subnivel->id ?>" <?= $subnivel->id == $datos['especialidad']->subnivel_id ? 'selected' : '' ?>><?= $subnivel->nombre ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <p id="error-subnivel" class="invalid-feedback">Debe seleccionar un subnivel de educación.</p>
                </div>
                <button id="btn-submit" type="submit" class="btn btn-primary">Actualizar</button>
                <a href="<?= RUTA_URL . "especialidades" ?>" class="btn btn-secondary">Regresar</a>
            </form>
        </div>
    </div>
</div>

<script>
    const base_url = "<?php echo RUTA_URL; ?>";
</script>
<script src="<?php echo RUTA_URL; ?>public/assets/js/pages/admin-ue/especialidades/create.js"></script>