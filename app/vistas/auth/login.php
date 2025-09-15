<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Iniciar Sesión</title>
    <link rel="shortcut icon" type="image/x-icon" href="<?= RUTA_URL ?>public/assets/img/favicon.ico" />
    <link href="<?php echo RUTA_URL; ?>public/assets/css/styles.css" rel="stylesheet" />
    <script src="<?php echo RUTA_URL; ?>public/assets/js/all.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-image: url('<?php echo RUTA_URL; ?>public/assets/img/loginFont.jpg');
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
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
                                                <?php foreach ($datos['perfiles'] as $perfil) : ?>
                                                    <option value="<?php echo $perfil->id_perfil; ?>"><?php echo $perfil->pe_nombre; ?></option>
                                                <?php endforeach ?>
                                            </select>
                                            <label for="perfil"><i class="fa-solid fa-user-gear"></i></i> Perfil</label>
                                            <p id="error-perfil" class="invalid-feedback"></p>
                                        </div>
                                        <div class="form-floating mb-3" id="period_group">
                                            <select class="form-select" name="periodo" id="periodo">
                                                <option value="">Seleccione...</option>
                                                <!-- Aquí van los periodos lectivos vigentes -->
                                                <?php
                                                $meses_abrev = array(0, "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
                                                $modalidadModelo = $this->modelo('Modalidad');
                                                $modalidades = $modalidadModelo->obtenerModalidades();
                                                $periodoLectivoModelo = $this->modelo('PeriodoLectivo');
                                                foreach ($modalidades as $modalidad) {
                                                    $code = $modalidad->id_modalidad;
                                                    $name = $modalidad->mo_nombre;
                                                ?>
                                                    <optgroup label='<?php echo $name; ?>'>
                                                        <?php $periodos = $periodoLectivoModelo->obtenerPeriodosL($code);
                                                        foreach ($periodos as $periodo) {
                                                            $code2 = $periodo->id_periodo_lectivo;
                                                            $fecha_inicial = explode("-", $periodo->pe_fecha_inicio);
                                                            $fecha_final = explode("-", $periodo->pe_fecha_fin);
                                                            $name2 = $meses_abrev[(int)$fecha_inicial[1]] . " " . $fecha_inicial[0] . " - " . $meses_abrev[(int)$fecha_final[1]] . " " . $fecha_final[0] . " [" . $periodo->pe_descripcion . "]";
                                                        ?>
                                                            <option value="<?php echo $code2; ?>"><?php echo $name2; ?></option>
                                                        <?php } ?>
                                                    </optgroup>
                                                <?php } ?>
                                            </select>
                                            <label for="periodo"><i class="fa-solid fa-calendar"></i> Periodo Lectivo</label>
                                            <p id="error-periodo" class="invalid-feedback"></p>
                                        </div>
                                        <div id="img_loader" style="display:none;text-align:center">
                                            <img src="<?= RUTA_URL ?>public/assets/img/ajax-loader-blue.GIF" alt="Procesando...">
                                        </div>
                                        <div id="mensaje">
                                            <!-- Aqui van los mensajes de error -->
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <!-- ¿Olvidó su contraseña? -->
                                            <a class="small" href="password.html"></a>
                                            <button class="btn btn-primary" type="submit" onclick="frmLogin(event);"><i class="fas fa-sign-in"></i> Ingresar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer style="text-align: center; color: #000; margin-top: -40px; font-weight: 600;">
                .: &copy; <?php echo date("  Y"); ?> - <?php echo $datos['nombreInstitucion']; ?> :.
            </footer>
        </div>
    </div>

    <!-- jQuery 3 -->
    <script src="<?php echo RUTA_URL ?>public/assets/js/jquery/jquery.min.js"></script>

    <script>
        const base_url = "<?php echo RUTA_URL; ?>";

        const periodo_group = document.querySelector("#period_group");
        periodo_group.style.display = "none";

        const usuario = document.getElementById("usuario");
        const clave = document.getElementById("clave");
        const perfil = document.getElementById("perfil");
        const periodo = document.getElementById("periodo");

        let perfilSeleccionado;

        // Agregamos un listener para el evento 'change'
        perfil.addEventListener('change', function(event) {
            // Obtenemos el valor seleccionado
            const valorSeleccionado = event.target.value;

            // Obtén el texto (innerHTML) del <option> seleccionado
            perfilSeleccionado = perfil.options[perfil.selectedIndex].innerHTML;

            if (perfilSeleccionado === 'ADMINISTRADOR' || perfilSeleccionado === 'TUTOR' || valorSeleccionado === "") {
                periodo_group.style.display = "none";
            } else {
                periodo_group.style.display = "block";
            }
        });

        function frmLogin(e) {
            e.preventDefault();

            // Oculto algún mensaje de error previo
            document.querySelector("#mensaje").style.display = "none";

            if (usuario.value == "" || clave.value == "" || perfil.value == "") {

                if (usuario.value == "") {
                    usuario.classList.add("is-invalid");
                    document.getElementById("error-usuario").innerHTML = "El campo Usuario es obligatorio.";
                } else {
                    usuario.classList.remove("is-invalid");
                    document.getElementById("error-usuario").innerHTML = "";
                }

                if (clave.value == "") {
                    clave.classList.add("is-invalid");
                    document.getElementById("error-clave").innerHTML = "El campo Contraseña es obligatorio.";
                } else {
                    clave.classList.remove("is-invalid");
                    document.getElementById("error-clave").innerHTML = "";
                }

                if (perfil.value == "") {
                    perfil.classList.add("is-invalid");
                    document.getElementById("error-perfil").innerHTML = "El campo Perfil es obligatorio.";
                } else {
                    perfil.classList.remove("is-invalid");
                    document.getElementById("error-perfil").innerHTML = "";
                }

            } else {

                if (perfilSeleccionado !== 'ADMINISTRADOR' && perfilSeleccionado !== 'TUTOR') {
                    if (periodo.value == "") {
                        periodo.classList.add("is-invalid");
                        document.getElementById("error-periodo").innerHTML = "El campo Periodo Lectivo es obligatorio.";
                    } else {
                        verificar_login();
                    }
                } else {
                    verificar_login();
                }

            }
        }

        async function verificar_login() {
            // Eliminar todos los mensajes de error
            usuario.classList.remove("is-invalid");
            document.getElementById("error-usuario").innerHTML = "";
            clave.classList.remove("is-invalid");
            document.getElementById("error-clave").innerHTML = "";
            perfil.classList.remove("is-invalid");
            document.getElementById("error-perfil").innerHTML = "";
            periodo.classList.remove("is-invalid");
            document.getElementById("error-periodo").innerHTML = "";
            // Desplegar el loader image
            document.querySelector("#img_loader").style.display = "block";
            // Obtener todos los campos a enviar mediante FormData
            let frmLogin = document.querySelector("#frmLogin");
            const data = new FormData(frmLogin);
            // Llamar al método auth/login que verifica si existe el usuario, clave y perfil
            try {
                let resp = await fetch("<?php echo RUTA_URL ?>Auth/login", {
                    method: "POST",
                    mode: "cors",
                    cache: "no-cache",
                    body: data,
                });
                json = await resp.json();
                // console.log(json);
                if (!json.error) {

                    //No hay error se redirecciona al dashboard correspondiente
                    console.log(json.pe_nombre);

                    switch (json.pe_nombre) {
                        case 'ADMINISTRADOR':
                            location.href = "<?php echo RUTA_URL ?>Admin/dashboard";
                            break;
                    
                        default:
                            alert("Todavía no se ha implementado el dashboard correspondiente.");
                            break;
                    }

                } else {

                    //No existe el usuario
                    var error = '<div class="alert alert-danger alert-dismissible">' +
                        '<p><i class="icon fa fa-ban"></i> Usuario o password o perfil incorrectos.</p>' +
                        '</div>';
                    $("#img_loader").css("display", "none");
                    $("#mensaje").html(error);
                    $("#mensaje").fadeIn("slow");
                    document.getElementById("usuario").focus();

                }
            } catch (error) {
                console.log("Ocurrió un error: " + error);
            }
        }
    </script>
</body>

</html>