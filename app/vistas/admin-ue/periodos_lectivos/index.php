<div class="container-fluid px-4">
    <div class="card mt-2">
        <div class="card-header">
            <i class="far fa-calendar  me-1"></i>
            Administración de Periodos Lectivos

            <a href="<?= RUTA_URL . "periodos_lectivos/create" ?>" class="btn btn-block btn-primary btn-sm float-end">
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
                    <table id="tbl_subniveles" class="table table-hover table-striped form-control-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID</th>
                                <th>Modalidad</th>
                                <th>Año Inicio</th>
                                <th>Año Fin</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $contador = 0;
                            foreach ($datos['periodos_lectivos'] as $v) {
                                $contador++;
                            ?>
                                <tr>
                                    <td><?= $contador ?></td>
                                    <td><?= $v->id_periodo_lectivo ?></td>
                                    <td><?= $v->mo_nombre ?></td>
                                    <td><?= $v->pe_anio_inicio ?></td>
                                    <td><?= $v->pe_anio_fin ?></td>
                                    <td><?= $v->pe_descripcion ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= RUTA_URL . "periodos_lectivos/edit/" . $v->id_periodo_lectivo ?>" class="btn btn-warning btn-sm" title="Editar"><span class="fa fa-pencil"></span></a>
                                            <button type="button" class="btn btn-danger btn-sm item-delete" onclick="eliminar(<?= $v->id_periodo_lectivo ?>)"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>