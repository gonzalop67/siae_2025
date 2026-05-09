<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Iniciar Sesión</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ RUTA_URL }}/public/assets/img/favicon.ico" />
    <link href="{{ RUTA_URL }}/public/assets/css/styles.css" rel="stylesheet" />
    <script src="{{ RUTA_URL }}/public/assets/js/all.js"></script>
    <style>
        body {
            background-image: url('{{ RUTA_URL }}/public/assets/img/loginFont.jpg');
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }

        footer {
            background: linear-gradient(transparent, #000a);
            color: #fff;
            font-family: Tahoma;
            font-weight: 300;
            font-size: 13px;
            margin-top: -40px;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">S.I.A.E.</h3>
                                </div>
                                <div class="card-body">
                                    <form id="frmLogin" action="" method="POST" autocomplete="off">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="usuario" name="usuario" type="text" placeholder="Ingrese usuario" autocomplete="username">
                                            <label for="usuario"><i class="fas fa-user"></i> Usuario</label>
                                            <p id="error-usuario" class="invalid-feedback"></p>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="clave" name="clave" type="password" placeholder="Ingrese contraseña" autocomplete="current-password">
                                            <label for="clave"><i class="fas fa-key"></i> Contraseña</label>
                                            <p id="error-clave" class="invalid-feedback"></p>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="perfil" id="perfil">
                                                <option value="">Seleccione...</option>
                                                <!-- Aquí van los perfiles -->
                                                @foreach ($perfiles as $perfil)
                                                <option value="{{ $perfil['id_perfil'] }}">{{ $perfil['pe_nombre'] }}</option>
                                                @endforeach
                                            </select>
                                            <label for="perfil"><i class="fa-solid fa-user-gear"></i></i> Perfil</label>
                                            <p id="error-perfil" class="invalid-feedback"></p>
                                        </div>
                                        <div id="img_loader" style="display:none;text-align:center">
                                            <img src="{{ RUTA_URL }}/public/assets/img/ajax-loader-blue.GIF" alt="Procesando...">
                                        </div>
                                        <div id="mensaje">
                                            <!-- Aqui van los mensajes de error -->
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <!-- ¿Olvidó su contraseña? -->
                                            <a class="small" href="{{ RUTA_URL }}/forgot_password">¿Olvidó su contraseña?</a>
                                            <button class="btn btn-primary" type="submit"><i class="fas fa-sign-in"></i> Ingresar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            </main>
        </div>
    </div>
    <div id="layoutAuthentication_footer">
        <footer>
            .: &copy; {{ date("  Y") }} - {{ $nom_institucion }} :.
        </footer>
    </div>
    <script>
        const base_url = "{{ RUTA_URL }}";
    </script>
    <script src="{{ RUTA_URL }}/public/assets/js/pages/auth/login.js"></script>
</body>

</html>