<div class="container-fluid px-4">
    <div class="card mt-2 mb-4">
        <div class="card-header">
            <i class="far fa-calendar me-1"></i>
            Crear Nuevo Periodo Lectivo
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['msg'])) : ?>
                <div class="alert alert-<?= $_SESSION['msg.type'] ?> alert-dismissible fade show" role="alert">
                    <p><i class="icon fa fa-<?= $_SESSION['msg.icon'] ?>"></i> <?= $_SESSION['msg.body'] ?></p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif ?>
            <form id="formulario" class="form-control-sm" action="<?= RUTA_URL . "periodos_lectivos/store" ?>" method="post">
                <div class="row mb-3">
                    <div class="col-lg-2">
                        <label for="pe_anio_inicio" class="form-label requerido">Año Inicial:</label>
                    </div>
                    <div class="col-lg-4">
                        <input type="text" class="form-control fuente9" value="" name="pe_anio_inicio" id="pe_anio_inicio" required>
                        <span id="error-pe_anio_inicio" class="invalid-feedback">Debe ingresar los cuatro dígitos del año inicial.</span>
                    </div>
                    <div class="col-lg-2">
                        <label for="pe_anio_fin" class="form-label requerido">Año Final:</label>
                    </div>
                    <div class="col-lg-4">
                        <input type="text" class="form-control fuente9" value="" name="pe_anio_fin" id="pe_anio_fin" required>
                        <span id="error-pe_anio_fin" class="invalid-feedback">Debe ingresar los cuatro dígitos del año final.</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-2">
                        <label for="pe_fecha_inicio" class="form-label requerido">Fecha de inicio:</label>
                    </div>
                    <div class="col-lg-4">
                        <input type="date" class="form-control fuente9" value="" name="pe_fecha_inicio" id="pe_fecha_inicio" required>
                        <span id="error-pe_fecha_inicio" class="invalid-feedback">Debe ingresar la fecha de inicio.</span>
                    </div>
                    <div class="col-lg-2">
                        <label for="pe_fecha_fin" class="form-label requerido">Fecha de fin:</label>
                    </div>
                    <div class="col-lg-4">
                        <input type="date" class="form-control fuente9" value="" name="pe_fecha_fin" id="pe_fecha_fin" required>
                        <span id="error-pe_fecha_fin" class="invalid-feedback">Debe ingresar la fecha de fin.</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-2">
                        <label for="pe_nota_minima" class="form-label requerido">Nota mínima:</label>
                    </div>
                    <div class="col-lg-4">
                        <input type="number" min="0.01" step="0.01" class="form-control fuente9" name="pe_nota_minima" id="pe_nota_minima" value="" required>
                        <span id="error-pe_nota_minima" class="invalid-feedback">Debe ingresar la nota mínima para el ingreso de notas.</span>
                    </div>
                    <div class="col-lg-2">
                        <label for="pe_nota_aprobacion" class="form-label requerido">Nota aprobación:</label>
                    </div>
                    <div class="col-lg-4">
                        <input name="pe_nota_aprobacion" type="number" min="7" max="10" step="0.01" class="form-control fuente9" id="pe_nota_aprobacion" value="" required>
                        <span id="error-pe_nota_aprobacion" class="invalid-feedback">Debe ingresar la nota mínima de aprobación.</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-2">
                        <label for="id_modalidad" class="form-label requerido">Modalidad:</label>
                    </div>
                    <div class="col-lg-4">
                        <select name="id_modalidad" id="id_modalidad" class="form-control fuente9" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($datos['modalidades'] as $v): ?>
                                <option value="<?= $v->id_modalidad ?>">
                                    <?= $v->mo_nombre ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span id="error-id_modalidad" class="invalid-feedback">Debe seleccionar la modalidad.</span>
                    </div>
                    <div class="col-lg-2">
                        <label for="quien_inserta_comp_id" class="form-label requerido">¿Quién inserta el comportamiento?:</label>
                    </div>
                    <div class="col-lg-4">
                        <select name="quien_inserta_comp_id" id="quien_inserta_comp_id" class="form-control fuente9" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($datos['quien_inserta_comportamiento'] as $v): ?>
                                <option value="<?= $v->id ?>">
                                    <?= $v->nombre ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span id="error-quien_inserta_comp_id" class="invalid-feedback">Debe seleccionar quien inserta el comportamiento.</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-6">
                        <label for="niveles" class="form-label requerido">Asociar Nivel de Educación:</label>
                        <?php foreach ($datos['niveles_educacion'] as $v) : ?>
                            <div>
                                <input type="checkbox" name="niveles[]" value="<?= $v->id_nivel_educacion ?>">
                                <?= $v->nombre ?>
                            </div>
                        <?php endforeach ?>
                        <span id="error-niveles" class="invalid-feedback">Debe seleccionar al menos un subnivel de educación.</span>
                    </div>
                    <div class="col-lg-6">
                        <label for="sub_periodos_evaluacion" class="form-label requerido">Asociar Sub Periodos de Evaluación:</label>
                        <?php foreach ($datos['sub_periodos_evaluacion'] as $v) : ?>
                            <div>
                                <input type="checkbox" name="sub_periodos[]" value="<?= $v->id_sub_periodo_evaluacion ?>">
                                <?= $v->pe_nombre ?>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
                <div class="form-group">
                    <button id="btn-save" type="submit" class="btn btn-success">Guardar</button>
                    <a href="<?= RUTA_URL . "periodos_lectivos" ?>" class="btn btn-secondary">Regresar</a>
                </div>
            </form>
        </div>
    </div>
</div>