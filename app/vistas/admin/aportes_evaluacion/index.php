<div class="container-fluid px-4">
    <div class="card mt-2">
        <div class="card-header">
            <i class="fa-solid fa-marker me-1"></i>
            Administración de Aportes de Evaluación

            <a href="<?= RUTA_URL . "aportes_evaluacion/create" ?>" class="btn btn-block btn-primary btn-sm float-end">
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
                                <th>Nombre</th>
                                <th>Abreviatura</th>
                                <th>Descripción</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $aporteModelo = $this->modelo('Aporte_evaluacion');
                            $aportes = $aporteModelo->obtenerAportesEvaluacion();

                            foreach ($aportes as $v) {
                            ?>
                                <tr>
                                    <td><?= $v->id_aporte_evaluacion ?></td>
                                    <td><?= $v->ap_nombre ?></td>
                                    <td><?= $v->ap_abreviatura ?></td>
                                    <td><?= $v->ap_descripcion ?></td>
                                    <td>
                                        <div class="btn-group float-end">
                                            <a href="<?= RUTA_URL . "aportes_evaluacion/edit/" . $v->id_aporte_evaluacion ?>" class="btn btn-warning btn-sm" title="Editar"><span class="fa fa-pencil"></span></a>
                                            <button type="button" class="btn btn-danger btn-sm item-delete" onclick="eliminar(<?= $v->id_aporte_evaluacion ?>)" title="Eliminar"><i class="fa fa-trash"></i></button>
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
    function eliminar(id) {
        Swal.fire({
            title: '¿Está seguro de eliminar este aporte de evaluación?',
            text: "¡Esta acción no se puede deshacer!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= RUTA_URL . "aportes_evaluacion/delete/" ?>" + id;
            }
        });
    }
</script>