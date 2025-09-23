<div class="container-fluid px-4">
    <div class="card mt-2">
        <div class="card-header">
            <i class="fas fa-building-columns me-1"></i>
            Administración de Instituciones Educativas
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

            <a href="<?= RUTA_URL . "instituciones/create" ?>" class="btn btn-block btn-primary btn-sm">
                <i class="fa fa-fw fa-plus-circle"></i> Nueva Institución
            </a>
            <hr>
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table id="tbl_usuarios" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Logo</th>
                                <th>Administrador</th>
                                <th>Email</th>
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
                                    <td><?= $v->us_shortname ?></td>
                                    <td><?= $v->in_email ?></td>
                                    <td><?= $v->in_direccion ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= RUTA_URL . "instituciones/edit/" . $v->id_institucion ?>" class="btn btn-warning btn-sm" title="Editar"><span class="fa fa-pencil"></span></a>
                                            <button type="button" class="btn btn-danger btn-sm item-delete" onclick="eliminar(<?= $v->id_institucion ?>)"><i class="fa fa-trash"></i></button>
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