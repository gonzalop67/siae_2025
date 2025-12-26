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

            <?php
            include RUTA_APP . "/vistas/layouts/includes/mensaje.php";
            ?> 

            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table id="tbl_areas" class="table table-hover table-striped form-control-sm">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>SubPeriodo</th>
                                <th>Nombre</th>
                                <th>Ponderación</th>
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
                                    <td><?= $v->ar_nombre ?></td>
                                    <td><?= $v->ar_nombre ?></td>
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