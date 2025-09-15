<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo NOMBRESITIO . $datos['titulo'] ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- <link rel="stylesheet" href="<?php echo RUTA_URL ?>/public/css/estilos.css">
    <link rel="stylesheet" href="<?php echo RUTA_URL ?>/public/assets/template/bootstrap/css/bootstrap.min.css"> -->

    <link href="<?php echo RUTA_URL ?>public/assets/css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <style>
        .avatar__img {
            width: 25px;
            border-radius: 50%;
        }
    </style>

    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="sb-nav-fixed">
    <?php require RUTA_APP . "/vistas/layouts/header.php" ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>