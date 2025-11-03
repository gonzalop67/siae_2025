<div class="container-fluid px-4">
    <div class="card mt-2">
        <div class="card-header">
            <i class="far fa-calendar me-1"></i>
            Administración de Jornadas

            <a href="<?= RUTA_URL . "jornadas/create" ?>" class="btn btn-block btn-dark btn-sm float-end">
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
                                <th>ID</th>
                                <th>Nombre</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $contador = 0;
                            if (count($datos['jornadas']) > 0) {
                                foreach ($datos['jornadas'] as $v) {
                                    $contador++;
                            ?>
                                    <tr data-index='<?= $v->id_jornada ?>' data-orden='<?= $v->jo_orden ?>'>
                                        <td><?= $contador ?></td>
                                        <td><?= $v->id_jornada ?></td>
                                        <td><?= $v->jo_nombre ?></td>
                                        <td>
                                            <div class="btn-group float-end">
                                                <a href="<?= RUTA_URL . "jornadas/edit/" . $v->id_jornada ?>" class="btn btn-warning btn-sm" title="Editar"><span class="fa fa-pencil"></span></a>
                                                <button type="button" class="btn btn-danger btn-sm item-delete" onclick="eliminar(<?= $v->id_jornada ?>)" title="Eliminar"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="100%" class="text-center">No se han ingresado Jornadas todavía...</td>
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

    $(document).ready(function () {
        $('table tbody').sortable({
            update: function(event, ui) {
                $(this).children().each(function(index) {
                    if ($(this).attr('data-orden') != (index + 1)) {
                        $(this).attr('data-orden', (index + 1)).addClass('updated');
                    }
                });

                saveNewPositions();
            }
        });
    });

    function saveNewPositions() {
        var positions = [];
        $('.updated').each(function() {
            positions.push([$(this).attr('data-index'), $(this).attr('data-orden')]);
            $(this).removeClass('updated');
        });

        $.ajax({
            url: base_url + "jornadas/saveNewPositions",
            method: 'POST',
            dataType: 'text',
            data: {
                positions: positions
            },
            success: function(response) {
                // console.log(response);
                window.location.href = base_url + "jornadas";
            }
        });
    }

    function eliminar(id) {
        const url = base_url + "jornadas/delete/" + id;
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