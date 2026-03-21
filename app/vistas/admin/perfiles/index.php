<div class="container-fluid px-4">
    <div class="card mt-2">
        <div class="card-header">
            <i class="fas fa-address-card  me-1"></i>
            Administración de Perfiles

            <a href="<?= RUTA_URL . "perfiles/create" ?>" class="btn btn-block btn-success btn-sm float-end">
                <i class="fa fa-fw fa-plus-circle"></i> Nuevo registro
            </a>
        </div>
        <div class="card-body">
            
            <?php
            include RUTA_APP . "/vistas/layouts/includes/mensaje.php";
            ?>

            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table id="tbl_usuarios" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Slug</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $contador = 0;
                            foreach ($datos['perfiles'] as $v) {
                                $contador++;
                            ?>
                                <tr>
                                    <td><?= $contador ?></td>
                                    <td><?= $v->id_perfil ?></td>
                                    <td><?= $v->pe_nombre ?></td>
                                    <td><?= $v->pe_slug ?></td>
                                    <td>
                                        <div class="btn-group float-end">
                                            <a href="<?= RUTA_URL . "perfiles/edit/" . $v->id_perfil ?>" class="btn btn-warning btn-sm" title="Editar"><span class="fa fa-pencil"></span></a>
                                            <button type="button" class="btn btn-danger btn-sm item-delete" onclick="eliminar(<?= $v->id_perfil ?>)"><i class="fa fa-trash" title="Eliminar"></i></button>
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
        const url = base_url + "perfiles/delete/" + id;
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