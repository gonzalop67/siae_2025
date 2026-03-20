<div class="container-fluid px-4">
    <div class="card mt-2">
        <div class="card-header">
            <i class="fa-solid fa-calendar-days me-1"></i>
            Administración de Subperiodos de Evaluación

            <a href="<?= RUTA_URL . "subperiodos_evaluacion/create" ?>" class="btn btn-block btn-success btn-sm float-end">
                <i class="fa fa-fw fa-plus-circle"></i> Nuevo registro
            </a>
        </div>
        <div class="card-body">
            <?php
            include RUTA_APP . "/vistas/layouts/includes/mensaje.php";
            ?>

            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table id="tbl_subperiodos" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Abreviatura</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $contador = 0;
                            if (!empty($datos['subperiodos_evaluacion'])) {
                                foreach ($datos['subperiodos_evaluacion'] as $v) {
                                    $contador++;
                            ?>
                                    <tr>
                                        <td><?= $contador ?></td>
                                        <td><?= $v->id_sub_periodo_evaluacion ?></td>
                                        <td><?= $v->pe_nombre ?></td>
                                        <td><?= $v->pe_abreviatura ?></td>
                                        <td>
                                            <div class="btn-group float-end">
                                                <a href="<?= RUTA_URL . "subperiodos_evaluacion/edit/" . $v->id_sub_periodo_evaluacion  ?>" class="btn btn-warning btn-sm" title="Editar"><span class="fa fa-pencil"></span></a>
                                                <button type="button" class="btn btn-danger btn-sm item-delete" onclick="eliminar(<?= $v->id_sub_periodo_evaluacion  ?>)" title="Eliminar"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="100%" class="text-center">Aún no se han definido subperiodos de evaluación</td>
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

<script>
    const base_url = "<?php echo RUTA_URL; ?>";

    function eliminar(id) {
        const url = base_url + "subperiodos_evaluacion/delete/" + id;
        Swal.fire({
            title: "¿Estás seguro de eliminar este registro?",
            text: "¡Una vez eliminado no podrá recuperarse!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sí, elimínalo!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }
</script>