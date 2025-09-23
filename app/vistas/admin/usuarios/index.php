<div class="container-fluid px-4">
    <div class="card mt-2">
        <div class="card-header">
            <i class="fas fa-users me-1"></i>
            Administración de Usuarios
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

            <a href="<?= RUTA_URL . "usuarios/create" ?>" class="btn btn-block btn-primary btn-sm">
                <i class="fa fa-fw fa-plus-circle"></i> Nuevo Usuario
            </a>
            <hr>
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table id="tbl_usuarios" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Id</th>
                                <th>Avatar</th>
                                <th>Nombre</th>
                                <th>Usuario</th>
                                <th>Activo</th>
                                <th>Perfiles</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $usuarioModelo = $this->modelo('Usuario');
                            $usuarios = $usuarioModelo->obtenerUsuarios();

                            $contador = 0;
                            foreach ($usuarios as $v) {
                                $contador++;
                            ?>
                                <tr>
                                    <td><?= $contador ?></td>
                                    <td><?= $v->id_usuario ?></td>
                                    <td>
                                        <img src="<?= RUTA_URL . "public/uploads/" . $v->us_foto; ?>" class="img-circle" width="50" alt="Avatar del Usuario">
                                    </td>
                                    <td><?= $v->us_apellidos . " " . $v->us_nombres; ?></td>
                                    <td><?= $v->us_login ?></td>
                                    <td>
                                        <?php if ($v->us_activo == 1) : ?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else : ?>
                                            <span class="badge bg-danger">Inactivo</span>
                                        <?php endif ?>
                                    </td>
                                    <?php
                                    $perfiles = $usuarioModelo->obtenerPerfiles($v->id_usuario);
                                    $cadena_perfiles = "";
                                    foreach ($perfiles as $perfil) {
                                        $cadena_perfiles .= $perfil->pe_nombre . ", ";
                                    }
                                    $cadena_perfiles = rtrim($cadena_perfiles, ", ");
                                    ?>
                                    <td>
                                        <?= $cadena_perfiles; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= RUTA_URL . "usuarios/edit/" . $v->id_usuario ?>" class="btn btn-warning btn-sm" title="Editar"><span class="fa fa-pencil"></span></a>
                                            <button type="button" class="btn btn-danger btn-sm item-delete" onclick="eliminar(<?= $v->id_usuario ?>)"><i class="fa fa-trash"></i></button>
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

    let table;
    let tableInitialized = false;

    if (tableInitialized) {
        table.destroy();
    }

    table = new DataTable('#tbl_usuarios', {
        columnDefs: [{
            orderable: false,
            targets: [0, 1, 2, 5, 6, 7]
        }],
        destroy: true,
        pageLength: 5,
        lengthMenu: [5, 10, 15, {
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
        const url = base_url + "usuarios/delete/" + id;
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
                // Swal.fire({
                //     title: "Deleted!",
                //     text: "Your file has been deleted."+url,
                //     icon: "success"
                // });
                window.location.href = url;
            }
        });
    }
</script>