<div class="container-fluid px-4">
    <div class="card mt-2">
        <div class="card-header">
            <i class="fas fa-graduation-cap me-1"></i>
            Matriculación de Estudiantes
        </div>
        <div class="card-body">
            <div class="form-group row mb-3 form-control-sm">
                <div class="col-lg-1 text-start">
                    <label for="paralelo" class="form-label">Paralelo:</label>
                </div>
                <div class="col-lg-11">
                    <select class="form-select" id="paralelo" name="paralelo">
                        <option value="">Seleccione...</option>
                        <?php foreach ($datos['paralelos'] as $v) : ?>
                            <option value="<?= $v->id_paralelo; ?>"><?= $v->cu_nombre . " " . $v->pa_nombre . " - " . $v->es_figura . " - " . $v->jo_nombre; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span id="error-paralelo" class="invalid-feedback">Debe seleccionar un paralelo...</span>
                </div>
            </div>
            <div id="botones" class="mb-3 hide">
                <a id="new_student" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newStudentModal"><i class="fa fa-plus-circle"></i> Nuevo Estudiante</a>

                <a id="inactive_students" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deletedStudentModal"><i class="fa fa-gear"></i> Estudiantes Inactivos</a>

                <!-- Navbar Search-->
                <form class="d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0 float-end">
                    <div class="input-group input-group-sm" style="width: 400px;">
                        <input class="form-control" type="text" placeholder="NOMBRE PARA HISTORICO DEL ESTUDIANTE..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                        <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
            <div id="conteo_estudiantes" class="row mb-3 form-control-sm hide">
                <div class="col-md-12">
                    <span id="contar_estudiantes_por_genero">
                        <!-- Aqui va el conteo de estudiantes por genero -->
                    </span>
                    
                    <div class="input-group-sm float-end" style="width: 400px;">
                        <input id="buscar_estudiante" class="form-control float-end" type="text" placeholder="BUSCAR ESTUDIANTE..." />
                    </div>
                </div>
            </div>
            <div id="lista_estudiantes" class="row hide">
                <div class="col-md-12 table-responsive">
                    <table id="t_estudiantes" class="table table-striped table-hover fuente9">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Id</th>
                                <th>Mat.</th>
                                <th>Apellidos</th>
                                <th>Nombres</th>
                                <th>DNI</th>
                                <th>Fec.Nacim.</th>
                                <th>Edad</th>
                                <th>Género</th>
                                <th>Retirado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbody_estudiantes">
                            <!-- Aquí se pintarán los registros recuperados de la BD mediante AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php require "modalInsert.php" ?>
</div>

<script>
    $(document).ready(function() {
        document.getElementById("error-paralelo").classList.add("is-invalid");
        document.getElementById("error-paralelo").style.display = "block";

        // DataTables
        

        //Datemask yyyy/mm/dd
        $('#fec_nac').inputmask('yyyy-mm-dd', {
            'placeholder': 'aaaa-mm-dd'
        });

        $.ajaxSetup({
            error: function(jqXHR, textStatus, errorThrown) {
                if (jqXHR.status === 0) {
                    alert('Not connect: Verify Network.');
                } else if (jqXHR.status == 404) {
                    alert('Requested page not found [404]');
                } else if (jqXHR.status == 500) {
                    alert('Internal Server Error [500].');
                } else if (textStatus === 'parsererror') {
                    alert('Requested JSON parse failed.');
                } else if (textStatus === 'timeout') {
                    alert('Time out error.');
                } else if (textStatus === 'abort') {
                    alert('Ajax request aborted.');
                } else {
                    alert('Uncaught Error: ' + jqXHR.responseText);
                }
            }
        });

        const expresiones = {
            reg_fecnac: /^([12]\d{3}-(0?[1-9]|1[0-2])-(0?[1-9]|[12]\d|3[01]))$/i,
            reg_cedula_ecuatoriana: /^\d{10}/,
            reg_nombres: /^([a-zA-Z ñáéíóúÑÁÉÍÓÚ]{3,64})$/i,
            reg_email: /^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,4})$/i
        };

        $("#paralelo").on('change', function() {
            let id_paralelo = $(this).val();

            if (id_paralelo == "") {
                $("#botones").fadeOut();
                $("#error-paralelo").fadeIn("slow");
                $("#lista_estudiantes").hide();
                $("#conteo_estudiantes").hide();
            } else {
                $("#botones").fadeIn("slow");
                $("#error-paralelo").fadeOut();
                listarEstudiantesParalelo(id_paralelo);
                contarEstudiantesParalelo(id_paralelo);
                $("#lista_estudiantes").show();
                $("#conteo_estudiantes").show();
            }
        });

        $("#tipo_documento").change(function() {
            if ($(this).val() === "") {
                $("#error-tipo_documento").html("Debe seleccionar un tipo de documento.");
                $("#error-tipo_documento").fadeIn("slow");
            } else {
                $("#error-tipo_documento").fadeOut();
            }
        });

        $("#dni").keyup(function() {
            if ($("#tipo_documento").val() == 1) {
                if (expresiones.reg_cedula_ecuatoriana.test($("#dni").val())) {
                    if (cedulaValida($("#dni").val())) {
                        ExisteDNI();
                    }
                } else {
                    document.getElementById('grupo__dni').classList.remove('formulario__grupo-correcto');
                    document.getElementById('grupo__dni').classList.add('formulario__grupo-incorrecto');
                    document.getElementById('icon__dni').classList.remove('fa-circle-check');
                    document.getElementById('icon__dni').classList.add('fa-circle-xmark');
                    document.getElementById('error-dni').style.display = "block";
                }
            } else {
                // 
            }
        });

        $("#apellidos").keyup(function() {
            if ($("#apellidos").val() !== "") {
                if (expresiones.reg_nombres.test($(this).val())) {
                    document.getElementById('grupo__apellidos').classList.remove('formulario__grupo-incorrecto');
                    document.getElementById('grupo__apellidos').classList.add('formulario__grupo-correcto');
                    document.getElementById('icon__apellidos').classList.remove('fa-circle-xmark');
                    document.getElementById('icon__apellidos').classList.add('fa-circle-check');
                    document.getElementById('error-apellidos').style.display = "none";
                } else {
                    document.getElementById('grupo__apellidos').classList.remove('formulario__grupo-correcto');
                    document.getElementById('grupo__apellidos').classList.add('formulario__grupo-incorrecto');
                    document.getElementById('icon__apellidos').classList.remove('fa-circle-check');
                    document.getElementById('icon__apellidos').classList.add('fa-circle-xmark');
                    document.getElementById('error-apellidos').style.display = "block";
                }
            }
        });

        $("#nombres").keyup(function() {
            if ($("#nombres").val() !== "") {
                if (expresiones.reg_nombres.test($(this).val())) {
                    document.getElementById('grupo__nombres').classList.remove('formulario__grupo-incorrecto');
                    document.getElementById('grupo__nombres').classList.add('formulario__grupo-correcto');
                    document.getElementById('icon__nombres').classList.remove('fa-circle-xmark');
                    document.getElementById('icon__nombres').classList.add('fa-circle-check');
                    document.getElementById('error-nombres').style.display = "none";
                } else {
                    document.getElementById('grupo__nombres').classList.remove('formulario__grupo-correcto');
                    document.getElementById('grupo__nombres').classList.add('formulario__grupo-incorrecto');
                    document.getElementById('icon__nombres').classList.remove('fa-circle-check');
                    document.getElementById('icon__nombres').classList.add('fa-circle-xmark');
                    document.getElementById('error-nombres').style.display = "block";
                }
            }
        });

        $("#fec_nac").keyup(function() {
            if (expresiones.reg_fecnac.test($(this).val())) {
                $("#edad").val(calcularEdad($(this).val()));
                $("#error-edad").hide();
            } else {
                $("#edad").val('');
            }
        });

        $("#email").keyup(function() {
            if ($("#email").val() !== "") {
                if (expresiones.reg_email.test($(this).val())) {
                    document.getElementById('grupo__email').classList.remove('formulario__grupo-incorrecto');
                    document.getElementById('grupo__email').classList.add('formulario__grupo-correcto');
                    document.getElementById('icon__email').classList.remove('fa-circle-xmark');
                    document.getElementById('icon__email').classList.add('fa-circle-check');
                    document.getElementById('error-email').style.display = "none";
                } else {
                    document.getElementById('grupo__email').classList.remove('formulario__grupo-correcto');
                    document.getElementById('grupo__email').classList.add('formulario__grupo-incorrecto');
                    document.getElementById('icon__email').classList.remove('fa-circle-check');
                    document.getElementById('icon__email').classList.add('fa-circle-xmark');
                    document.getElementById('error-email').style.display = "block";
                }
            }
        });

    });

    function cedulaValida(cedula) {
        var total = 0;
        var longitud = cedula.length;
        var longcheck = longitud - 1;

        if (longitud != 10) {
            return false;
        }

        if (cedula !== "" && longitud === 10) {
            for (i = 0; i < longcheck; i++) {
                if (i % 2 === 0) {
                    var aux = cedula.charAt(i) * 2;
                    if (aux > 9) aux -= 9;
                    total += aux;
                } else {
                    total += parseInt(cedula.charAt(i)); // parseInt o concatenará en lugar de sumar
                }
            }

            total = total % 10 ? 10 - total % 10 : 0;

            return cedula.charAt(longitud - 1) == total;
        }
    }

    function calcularEdad(fec_nacim) {
        //Aqui se va a calcular la edad a partir de la fecha de nacimiento
        var hoy = new Date();
        var fec_nac = new Date(fec_nacim);
        var edad = hoy.getFullYear() - fec_nac.getFullYear();
        var m = hoy.getMonth() - fec_nac.getMonth();

        if (m < 0 || (m == 0 && hoy.getDate() < fec_nac.getDate())) {
            edad--;
        }

        return edad;
    }

    function ExisteDNI() {
        $.ajax({
            type: "POST",
            url: "<?= RUTA_URL ?>/Estudiantes/existeDNI",
            data: {
                dni: $("#dni").val()
            },
            dataType: "json",
            success: function(response) {
                //console.log(response);
                const mensaje_anterior = document.getElementById('error-dni').innerHTML;
                if (response.ok) {
                    document.getElementById('grupo__dni').classList.remove('formulario__grupo-correcto');
                    document.getElementById('grupo__dni').classList.add('formulario__grupo-incorrecto');
                    document.getElementById('icon__dni').classList.remove('fa-circle-check');
                    document.getElementById('icon__dni').classList.add('fa-circle-xmark');
                    document.getElementById('error-dni').innerHTML = "Ya existe el número de cédula en la Base de Datos";
                    document.getElementById('error-dni').style.display = "block";
                    document.getElementById('error-dni').innerHTML = mensaje_anterior;
                } else {
                    document.getElementById('grupo__dni').classList.remove('formulario__grupo-incorrecto');
                    document.getElementById('grupo__dni').classList.add('formulario__grupo-correcto');
                    document.getElementById('icon__dni').classList.remove('fa-circle-xmark');
                    document.getElementById('icon__dni').classList.add('fa-circle-check');
                    document.getElementById('error-dni').style.display = "none";
                }
            }
        });
    }

    function contarEstudiantesParalelo(id_paralelo) {
        $.ajax({
            type: "post",
            url: "<?= RUTA_URL ?>/matriculacion/contar_estudiantes_por_genero",
            data: "id_paralelo=" + id_paralelo,
            dataType: "html",
            success: function(response) {
                console.log(response);
                $("#contar_estudiantes_por_genero").html(response);
            }
        });
    }

    function listarEstudiantesParalelo(id_paralelo) {
        $.ajax({
            url: "<?= RUTA_URL ?>/matriculacion/listar",
            method: "POST",
            data: {
                id_paralelo: id_paralelo
            },
            dataType: "html",
            success: function(response) {
                $("#t_estudiantes tbody").html(response);
            }
        });
    }

    function insertarEstudiante() {
        let cont_errores = 0;

        const paralelo = $("#paralelo").val().trim();

        const cedula = $("#dni").val().trim();
        const email = $("#email").val().trim();
        const sector = $("#sector").val().trim();
        const genero = $("#genero").val().trim();
        const nombres = $("#nombres").val().trim();
        const telefono = $("#telefono").val().trim();
        const direccion = $("#direccion").val().trim();
        const apellidos = $("#apellidos").val().trim();
        const nacionalidad = $("#nacionalidad").val().trim();
        const tipo_documento = $("#tipo_documento").val().trim();
        const fec_nacim = $("#fec_nac").val().trim();

        const reg_cedula = /^([A-Z0-9.]{4,10})$/i;
        const reg_nombres = /^([a-zA-Z ñáéíóúÑÁÉÍÓÚ]{3,64})$/i;
        const reg_fecnac = /^([12]\d{3}-(0?[1-9]|1[0-2])-(0?[1-9]|[12]\d|3[01]))$/i;
        const reg_email = /^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,4})$/i;

        if (paralelo == "") {
            Swal.fire({
                title: "Ocurrió un error inesperado!",
                text: "Debe seleccionar un paralelo.",
                icon: "error",
            });
            $("#error-paralelo").html("Debe seleccionar un paralelo...");
            $("#error-paralelo").fadeIn("slow");
            cont_errores++;
        } else {
            $("#error-paralelo").fadeOut();
        }

        if (tipo_documento == "") {
            $("#error-tipo_documento").html("Debe seleccionar un tipo de documento...");
            $("#error-tipo_documento").fadeIn("slow");
            cont_errores++;
        } else {
            $("#error-tipo_documento").fadeOut();
        }

        if (cedula == "") {
            $("#error-dni").html("Debe ingresar el DNI...");
            $("#error-dni").fadeIn();
            cont_errores++;
        } else if (cedula.length != 0 && !reg_cedula.test(cedula)) {
            $("#error-dni").html("El DNI del estudiante no tiene un formato válido.");
            $("#error-dni").fadeIn();
            cont_errores++;
        } else if (tipo_documento == 1 && !cedulaValida(cedula)) {
            $("#error-dni").html("La cédula ingresada no es válida.");
            $("#error-dni").fadeIn("slow");
            cont_errores++;
        } else {
            $("#error-dni").fadeOut();
        }

        if (apellidos == "") {
            $("#error-apellidos").html("Debe ingresar los apellidos...");
            $("#error-apellidos").fadeIn();
            cont_errores++;
        } else if (!reg_nombres.test(apellidos)) {
            $("#error-apellidos").html("Los apellidos del estudiante deben contener entre 4 y 32 caracteres alfabéticos y espacio entre apellidos.");
            $("#error-apellidos").fadeIn();
            cont_errores++;
        } else {
            $("#error-apellidos").fadeOut();
        }

        if (nombres == "") {
            $("#error-nombres").html("Debe ingresar los nombres...");
            $("#error-nombres").fadeIn();
            cont_errores++;
        } else if (!reg_nombres.test(nombres)) {
            $("#error-nombres").html("Los nombres del estudiante deben contener entre 4 y 32 caracteres alfabéticos y espacio entre nombres.");
            $("#error-nombres").fadeIn();
            cont_errores++;
        } else {
            $("#error-nombres").fadeOut();
        }

        if (fec_nacim == "") {
            $("#error-fec_nac").html("Debe ingresar la fecha de nacimiento...");
            $("#error-fec_nac").fadeIn();
            cont_errores++;
        } else if (!reg_fecnac.test(fec_nacim)) {
            $("#error-fec_nac").html("La fecha de nacimiento debe tener el formato aaaa-mm-dd");
            $("#error-fec_nac").fadeIn();
            cont_errores++;
        } else {
            $("#error-fec_nac").fadeOut();
            $("#edad").val(calcularEdad(fec_nacim));
        }

        if (direccion == "") {
            $("#error-direccion").html("Debe ingresar la dirección de domicilio del estudiante...");
            $("#error-direccion").fadeIn();
            cont_errores++;
        } else {
            $("#error-direccion").fadeOut();
        }

        if (sector == "") {
            $("#error-sector").html("Debe ingresar el sector del domicilio...");
            $("#error-sector").fadeIn();
            cont_errores++;
        } else {
            $("#error-sector").fadeOut();
        }

        if (telefono == "") {
            $("#error-telefono").html("Debe ingresar el(los) número(s) de teléfono(s)...");
            $("#error-telefono").fadeIn();
            cont_errores++;
        } else {
            $("#error-telefono").fadeOut();
        }

        if (email.length != 0 && !reg_email.test(email)) {
            $("#error-email").html("Correo electrónico no válido.");
            $("#error-email").fadeIn();
            cont_errores++;
        } else {
            $("#error-email").fadeOut();
        }

        if (genero == "") {
            $("#error-genero").html("Debe seleccionar el género...");
            $("#error-genero").fadeIn();
            cont_errores++;
        } else {
            $("#error-genero").fadeOut();
        }

        if (nacionalidad == "") {
            $("#error-nacionalidad").html("Debe seleccionar la nacionalidad...");
            $("#error-nacionalidad").fadeIn();
            cont_errores++;
        } else {
            $("#error-nacionalidad").fadeOut();
        }

        if (cont_errores == 0) {
            // Aquí vamos a insertar el estudiante mediante AJAX
            $.ajax({
                url: "<?= RUTA_URL ?>/matriculacion/insert",
                method: "POST",
                data: {
                    id_paralelo: paralelo,
                    id_tipo_documento: tipo_documento,
                    es_cedula: cedula.toUpperCase(),
                    es_apellidos: apellidos.toUpperCase(),
                    es_nombres: nombres.toUpperCase(),
                    es_fec_nacim: fec_nacim,
                    es_direccion: direccion.toUpperCase(),
                    es_sector: sector.toUpperCase(),
                    es_telefono: telefono,
                    es_email: email.toLowerCase(),
                    id_def_genero: genero,
                    id_def_nacionalidad: nacionalidad,
                    id_paralelo: paralelo
                },
                dataType: "json",
                success: function(response) {
                    $('#newStudentModal').modal('hide');
                    Swal.fire({
                        title: response.titulo,
                        text: response.mensaje,
                        icon: response.tipo_mensaje,
                        confirmButtonText: 'Aceptar'
                    });
                    listarEstudiantesParalelo(paralelo);
                    contarEstudiantesParalelo(paralelo);
                    document.getElementById('grupo__dni').classList.remove('formulario__grupo-correcto');
                    document.getElementById('grupo__apellidos').classList.remove('formulario__grupo-correcto');
                    document.getElementById('grupo__nombres').classList.remove('formulario__grupo-correcto');
                    document.getElementById('grupo__email').classList.remove('formulario__grupo-correcto');
                    $("#formulario")[0].reset();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    }
</script>