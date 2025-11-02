<div class="container-fluid px-4">
    <div class="card mt-2">
        <div class="card-header">
            <i class="fas fa-users me-1"></i>
            Administración de Cursos

            <a href="<?= RUTA_URL . "cursos/create" ?>" class="btn btn-block btn-dark btn-sm float-end">
                <i class="fa fa-fw fa-plus-circle"></i> Nuevo Curso
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
                    <table id="tbl_cursos" class="table table-hover table-striped form-control-sm">
                        <thead>
                            <tr>
                                <th>Nro.</th>
                                <th>Subnivel</th>
                                <th>Especialidad</th>
                                <th>Nombre</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cursoModelo = $this->modelo('Curso');
                            $cursos = $cursoModelo->obtenerCursos();
                            $especialidadModelo = $this->modelo('Especialidad');

                            if (count($cursos) > 0) {
                                $contador = 0;
                                foreach ($cursos as $v) {
                                    $contador++;
                            ?>
                                    <tr data-index='<?= $v->id_curso_subnivel ?>' data-orden='<?= $v->orden ?>'>
                                        <td><?= $contador ?></td>
                                        <td><?= $v->nombre ?></td>
                                        <td><?= $v->es_figura ?></td>
                                        <td><?= $v->cu_nombre ?></td>
                                        <td>
                                            <div class="btn-group float-end">
                                                <a href="<?= RUTA_URL . "cursos/edit/" . $v->id_curso ?>" class="btn btn-warning btn-sm" title="Editar"><span class="fa fa-pencil"></span></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="100%" class="text-center">No se han ingresado Cursos todavía...</td>
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
            url: base_url + "cursos/saveNewPositions",
            method: 'POST',
            dataType: 'text',
            data: {
                positions: positions
            },
            success: function(response) {
                // console.log(response);
                window.location.href = base_url + "cursos";
            }
        });
    }
</script>