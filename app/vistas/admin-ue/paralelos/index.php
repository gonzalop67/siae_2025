<div class="container-fluid px-4">
    <div class="card mt-2">
        <div class="card-header">
            <i class="fas fa-graduation-cap me-1"></i>
            Administración de Paralelos

            <a href="<?= RUTA_URL . "paralelos/create" ?>" class="btn btn-block btn-dark btn-sm float-end">
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
                    <table id="tbl_especialidades" class="table table-hover table-striped form-control-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Periodo Lectivo</th>
                                <th>Especialidad</th>
                                <th>Curso</th>
                                <th>Nombre</th>
                                <th>Jornada</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $contador = 0;
                            if (count($datos['paralelos']) > 0) {
                                $meses_abrev = array(0, "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
                                foreach ($datos['paralelos'] as $v) {
                                    $contador++;
                                    $fecha_inicial = explode("-", $v->pe_fecha_inicio);
                                    $fecha_final = explode("-", $v->pe_fecha_fin);
                                    $nombrePL = "[" . $v->mo_nombre . "] " . $meses_abrev[(int)$fecha_inicial[1]] . " " . $fecha_inicial[0] . " - " . $meses_abrev[(int)$fecha_final[1]] . " " . $fecha_final[0];
                            ?>
                                    <tr>
                                        <td><?= $contador ?></td>
                                        <td><?= $nombrePL ?></td>
                                        <td><?= $v->es_figura ?></td>
                                        <td><?= $v->cu_nombre ?></td>
                                        <td><?= $v->pa_nombre ?></td>
                                        <td><?= $v->jo_nombre ?></td>
                                        <td>
                                            <div class="btn-group float-end">
                                                <a href="<?= RUTA_URL . "paralelos/edit/" . $v->id_paralelo ?>" class="btn btn-warning btn-sm" title="Editar"><span class="fa fa-pencil"></span></a>
                                                <button type="button" class="btn btn-danger btn-sm item-delete" onclick="eliminar(<?= $v->id_paralelo ?>)" title="Eliminar"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="100%" class="text-center">No se han ingresado Paralelos todavía...</td>
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
        const url = base_url + "paralelos/delete/" + id;
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