<?php
// session_start();
if (!isset($_SESSION['usuario_logueado'])) {
    redireccionar('/auth');
}
$avatar_user = "";
if (!empty($_SESSION['avatar_user'])) {
    $avatar_user = RUTA_URL . "public/uploads/" . trim($_SESSION['avatar_user']);
} else {
    $avatar_user = RUTA_URL . "public/assets/img/teacher-male-avatar.png";
}
// Obtener el número de comentarios
$comentarioModelo = $this->modelo('Comentario');
$nro_comentarios = $comentarioModelo->contarComentarios();
// Obtener el id del periodo lectivo actual más reciente
$periodoLectivoModelo = $this->modelo('PeriodoLectivo');
$nombrePerfil = strtolower($_SESSION['nombrePerfil']);
if (!isset($_SESSION['id_periodo_lectivo'])) {
    if ($nombrePerfil === "administrador") {
        $id_periodo_lectivo = $periodoLectivoModelo->obtenerIdPeriodoLectivoActual();
    } else if ($nombrePerfil === "tutor") {
        //
    }
} else {
    $id_periodo_lectivo = $_SESSION['id_periodo_lectivo'];
}
//Obtengo los años de inicio y de fin del periodo lectivo actual
$periodo_lectivo = $periodoLectivoModelo->obtenerPeriodoLectivo($id_periodo_lectivo);
$nombrePeriodoLectivo = $periodo_lectivo->pe_anio_inicio . " - " . $periodo_lectivo->pe_anio_fin;
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title><?php echo NOMBRESITIO . $datos['titulo'] ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="<?= RUTA_URL ?>public/assets/img/favicon.ico" />

    <link rel="stylesheet" href="//cdn.datatables.net/2.0.1/css/dataTables.dataTables.min.css">

    <link href="<?php echo RUTA_URL ?>public/assets/css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo RUTA_URL ?>public/assets/css/custom.css">

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <!-- jQuery 3 -->
    <script src="<?php echo RUTA_URL ?>public/assets/js/jquery/jquery.min.js"></script>

    <!-- DataTables -->
    <script src="//cdn.datatables.net/2.0.1/js/dataTables.min.js"></script>

    <!-- sweetalert 2 -->
    <link rel="stylesheet" href="<?= RUTA_URL ?>public/assets/js/sweetalert2/sweetalert2.min.css">
    <script src="<?= RUTA_URL ?>public/assets/js/sweetalert2/sweetalert2.min.js"></script>

    <!-- Toastr -->
    <link rel="stylesheet" href="<?= RUTA_URL ?>public/assets/js/toastr/toastr.min.css">
    <script src="<?= RUTA_URL ?>public/assets/js/toastr/toastr.min.js"></script>

    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="sb-nav-fixed">
    <?php require RUTA_APP . "/vistas/layouts/header.php" ?>

    <div id="layoutSidenav">
        <?php require RUTA_APP . "/vistas/layouts/aside.php" ?>
        <div id="layoutSidenav_content">
            <main>
                <?php require RUTA_APP . "/vistas/" . $datos['nombreVista'] ?>
            </main>
            <?php require RUTA_APP . "/vistas/layouts/footer.php" ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?= RUTA_URL ?>public/assets/js/scripts.js"></script>

    <script>
        $(document).ready(function() {
            // $("nav.sb-sidenav-menu-nested").find("a.active").parent().parent().prev().css("background-color", "yellow");
            $("nav.sb-sidenav-menu-nested").find("a.active").parent().parent().prev().addClass('active');

            $("nav.sb-sidenav-menu-nested").find("a.active").parent().parent().addClass('show');

            //Autoclose
            window.setTimeout(function() {
                $(".alert").fadeOut(1500, 0);
            }, 5000); //5 segundos y desaparece

            $(".close").on('click', function() {
                $(".alert").css("display", "none");
            });
        });
    </script>
</body>

</html>