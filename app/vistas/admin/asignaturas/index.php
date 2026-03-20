<div class="container-fluid px-4">
    <div class="card mt-2">
        <div class="card-header">
            <i class="fas fa-graduation-cap me-1"></i>
            Administración de Asignaturas

            <a href="<?= RUTA_URL . "asignaturas/create" ?>" class="btn btn-block btn-success btn-sm float-end">
                <i class="fa fa-fw fa-plus-circle"></i> Nuevo registro
            </a>
        </div>
        <div class="card-body">

            <?php
            include RUTA_APP . "/vistas/layouts/includes/mensaje.php";
            ?>

            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table id="t_asignaturas" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Area</th>
                                <th>Nombre</th>
                                <th>Abreviatura</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbody_asignaturas">
                            <?php
                            if (count($datos['asignaturas']) > 0) {
                            ?>
                                <?php foreach ($datos['asignaturas'] as $v) : ?>
                                    <tr>
                                        <td><?= $v->id_asignatura ?></td>
                                        <td><?= $v->ar_nombre ?></td>
                                        <td><?= $v->as_nombre ?></td>
                                        <td><?= $v->as_abreviatura ?></td>
                                        <td>
                                            <div class="btn-group float-end">
                                                <a href="<?= RUTA_URL . "asignaturas/edit/" . $v->id_asignatura ?>" class="btn btn-warning btn-sm" title="Editar"><span class="fa fa-pencil"></span></a>
                                                <button type="button" class="btn btn-danger btn-sm item-delete" onclick="eliminar(<?= $v->id_asignatura ?>)" title="Eliminar"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            <?php } else { ?>

                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    const base_url = "<?php echo RUTA_URL; ?>";

    let table;
    let tableInitialized = false;

    if (tableInitialized) {
        table.destroy();
    }

    table = new DataTable('#t_asignaturas', {
        columnDefs: [{
            orderable: false,
            targets: [3, 4]
        }],
        destroy: true,
        pageLength: 10,
        lengthMenu: [10, 15, 20, {
            label: 'Todos',
            value: -1
        }],
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla =(",
            "sInfo": "Registros del _START_ al _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Registros del 0 al 0 de 0 registros",
            "sInfoFiltered": "-",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            },
            "buttons": {
                "copy": "Copiar",
                "colvis": "Visibilidad"
            }
        }
    });

    tableInitialized = true;

    function eliminar(id) {
        const url = base_url + "asignaturas/delete/" + id;
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