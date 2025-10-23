<link rel="stylesheet" href="<?php echo RUTA_URL ?>/public/assets/css/jquery.nestable.css">
<div class="container-fluid px-4">
    <div class="card mt-2">
        <div class="card-header">
            <i class="fas fa-bars me-1"></i>
            Administración de Menús

            <a href="<?= RUTA_URL . "menus/create" ?>" class="btn btn-block btn-primary btn-sm float-end">
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
                <div class="col-lg-12 col-md-12 col-sm-12 table-responsive">
                    <!-- Aquí va el listado de menús -->
                    <div id="menu">
                        <div class="dd" id="nestable">
                            <ol class="dd-list">
                                <?php
                                $menuModelo = $this->modelo('Menu');
                                $menusNivel1 = $menuModelo->obtenerMenusPadres();
                                foreach ($menusNivel1 as $menu) {
                                ?>
                                    <li class="dd-item dd3-item" data-id="<?php echo $menu->id_menu; ?>">
                                        <div class="dd-handle dd3-handle"></div>
                                        <div class="dd3-content menu_link">
                                            <a href="<?php echo RUTA_URL; ?>/menus/edit/<?php echo $menu->id_menu; ?>"><?php echo "(" . $menu->pe_nombre . ") " . $menu->mnu_texto; ?></a>
                                            <a href="<?php echo RUTA_URL; ?>/menus/delete/<?php echo $menu->id_menu; ?>" class="eliminar-menu float-end" title="Eliminar este menú"><i class="text-danger fas fa-trash-alt"></i></a>
                                        </div>
                                        <?php
                                        $menusNivel2 = $menuModelo->obtenerMenusHijos($menu->id_menu);
                                        if (count($menusNivel2) > 0) {
                                        ?>
                                            <ol class="dd-list">
                                                <?php
                                                foreach ($menusNivel2 as $menu2) {
                                                ?>
                                                    <li class="dd-item dd3-item" data-id="<?php echo $menu2->id_menu; ?>">
                                                        <div class="dd-handle dd3-handle"></div>
                                                        <div class="dd3-content menu_link">
                                                            <a href="<?php echo RUTA_URL; ?>/menus/edit/<?php echo $menu2->id_menu; ?>"><?php echo $menu2->mnu_texto; ?></a>
                                                            <a href="<?php echo RUTA_URL; ?>/menus/delete/<?php echo $menu2->id_menu; ?>" class="eliminar-menu float-end" title="Eliminar este menú"><i class="text-danger far fa-trash-alt"></i></a>
                                                        </div>
                                                    </li>
                                                <?php
                                                }
                                                ?>
                                            </ol>
                                        <?php
                                        }
                                        ?>
                                    </li>
                                <?php
                                }
                                ?>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo RUTA_URL ?>/public/assets/js/jquery.nestable.js"></script>
<script>
    $(document).ready(function() {
        $('#nestable').nestable().on('change', function() {
            $.ajax({
                url: "<?php echo RUTA_URL; ?>menus/guardarOrden",
                type: 'POST',
                data: {
                    menu: $('#nestable').nestable('serialize')
                },
                success: function(respuesta) {
                    location.href = "<?php echo RUTA_URL ?>menus";
                }
            });
        });
        $('#nestable').nestable('expandAll');
        $('.eliminar-menu').on('click', function(event) {
            event.preventDefault();
            const url = $(this).attr('href');
            Swal.fire({
                title: "¿Está seguro que quiere eliminar el registro?",
                text: "No podrá recuperar el registro que va a ser eliminado!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sí, elimínelo!",
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
</script>