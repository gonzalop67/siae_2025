<div class="container-fluid px-4">
    <div class="card mt-2">
        <div class="card-header">
            <i class="fas fa-building-columns me-1"></i>
            Administración de Instituciones Educativas

            <a href="<?= RUTA_URL . "instituciones/create" ?>" class="btn btn-block btn-success btn-sm float-end">
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
                                <th>Logo</th>
                                <th>Nombre</th>
                                <th>Administrador</th>
                                <th>Dirección</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $contador = 0;
                            foreach ($datos['instituciones'] as $v) {
                                $contador++;
                            ?>
                                <tr>
                                    <td><?= $contador ?></td>
                                    <td>
                                        <img src="<?= RUTA_URL . "public/uploads/" . $v->in_logo; ?>" class="img-circle" width="50" alt="Logo de la Institución">
                                    </td>
                                    <td><?= $v->in_nombre ?></td>
                                    <td><?= $v->us_shortname ?></td>
                                    <td><?= $v->in_direccion ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= RUTA_URL . "instituciones/edit/" . $v->id_institucion ?>" class="btn btn-warning btn-sm" title="Editar"><span class="fa fa-pencil"></span></a>
                                            <button type="button" class="btn btn-danger btn-sm item-delete" onclick="eliminar(<?= $v->id_institucion ?>)" title="Eliminar"><i class="fa fa-trash"></i></button>
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
        const url = base_url + "instituciones/delete/" + id;
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