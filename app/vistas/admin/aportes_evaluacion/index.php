<div class="container-fluid px-4">
    <div class="card mt-2">
        <div class="card-header">
            <i class="fa-solid fa-marker me-1"></i>
            Administración de Aportes de Evaluación

            <a href="<?= RUTA_URL . "aportes_evaluacion/create" ?>" class="btn btn-block btn-dark btn-sm float-end">
                <i class="fa fa-fw fa-plus-circle"></i> Nuevo registro
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

            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table id="tbl_areas" class="table table-hover table-striped form-control-sm">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nombre</th>
                                <th>Activo</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $areaModelo = $this->modelo('Area');
                            $areas = $areaModelo->obtenerAreas();

                            foreach ($areas as $v) {
                            ?>
                                <tr>
                                    <td><?= $v->id_area ?></td>
                                    <td><?= $v->ar_nombre ?></td>
                                    <td>
                                        <?php if ($v->ar_activo == 1) : ?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else : ?>
                                            <span class="badge bg-danger">Inactivo</span>
                                        <?php endif ?>
                                    </td>
                                    <td>
                                        <div class="btn-group float-end">
                                            <a href="<?= RUTA_URL . "areas/edit/" . $v->id_area ?>" class="btn btn-warning btn-sm" title="Editar"><span class="fa fa-pencil"></span></a>
                                            <button type="button" class="btn btn-danger btn-sm item-delete" onclick="eliminar(<?= $v->id_area ?>)" title="Eliminar"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>