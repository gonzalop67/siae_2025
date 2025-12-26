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