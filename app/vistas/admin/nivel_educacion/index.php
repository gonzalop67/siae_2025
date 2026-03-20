<div class="container-fluid px-4">
    <div class="card mt-2">
        <div class="card-header">
            <i class="fa-solid fa-marker me-1"></i>
            Administración de Niveles de Evaluación

            <a href="<?= RUTA_URL . "niveles_educacion/create" ?>" class="btn btn-block btn-success btn-sm float-end">
                <i class="fa fa-fw fa-plus-circle"></i> Nuevo registro
            </a>
        </div>
        <div class="card-body">

            <?php
            include RUTA_APP . "/vistas/layouts/includes/mensaje.php";
            ?> 

            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table id="tbl_niveles_educacion" class="table table-hover table-striped form-control-sm">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nombre</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            foreach ($datos["niveles_educacion"] as $v) {
                            ?>
                                <tr>
                                    <td><?= $v->id ?></td>
                                    <td><?= $v->nombre ?></td>
                                    <td>
                                        <div class="btn-group float-end">
                                            <a href="<?= RUTA_URL . "niveles_educacion/edit/" . $v->id ?>" class="btn btn-warning btn-sm" title="Editar"><span class="fa fa-pencil"></span></a>
                                            <button type="button" class="btn btn-danger btn-sm item-delete" onclick="eliminar(<?= $v->id ?>)" title="Eliminar"><i class="fa fa-trash"></i></button>
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

<script>
    const base_url = "<?php echo RUTA_URL; ?>";

    function eliminar(id) {
        const url = base_url + "niveles_educacion/delete/" + id;
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