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
                    <div id="contar_estudiantes_por_genero">
                        <!-- Aqui va el conteo de estudiantes por genero -->
                    </div>
                </div>
            </div>
            <div id="lista_estudiantes" class="row hide">
                <div class="col-md-12 table-responsive">
                    <table id="t_estudiantes" class="table form-control-sm">
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
    <?php include_once "modalInsert.php" ?>
</div>

<script>
    $(document).ready(function() {

        document.getElementById("error-paralelo").classList.add("is-invalid");
        document.getElementById("error-paralelo").style.display = "block";

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

        const reg_fecnac = /^([12]\d{3}-(0?[1-9]|1[0-2])-(0?[1-9]|[12]\d|3[01]))$/i;

        $("#fec_nac").keyup(function() {
            if (reg_fecnac.test($(this).val())) {
                $("#edad").val(calcularEdad($(this).val()));
            } else {
                $("#edad").val('');
            }
        });

        const reg_cedula = /^\d{10}/;

        $("#dni").keyup(function() {
            if ($("#tipo_documento").val() == 1) {
                if (reg_cedula.test($(this).val())) {
                    if (cedulaValida($(this).val())) {
                        document.getElementById('grupo__dni').classList.remove('formulario__grupo-incorrecto');
                        document.getElementById('grupo__dni').classList.add('formulario__grupo-correcto');
                        document.querySelector('.formulario__validacion-dni').classList.remove('fa-circle-xmark');
                        document.querySelector('.formulario__validacion-dni').classList.add('fa-circle-check');
                        document.getElementById('error-dni').style.display = "none";
                    }
                } else {
                    document.getElementById('grupo__dni').classList.remove('formulario__grupo-correcto');
                    document.getElementById('grupo__dni').classList.add('formulario__grupo-incorrecto');
                    document.querySelector('.formulario__validacion-dni').classList.remove('fa-circle-check');
                    document.querySelector('.formulario__validacion-dni').classList.add('fa-circle-xmark');
                    document.getElementById('error-dni').style.display = "block";
                }
            }
        });

        $("#tipo_documento").change(function() {
            if ($(this).val != 1) {
                document.getElementById('grupo__dni').classList.remove('formulario__grupo-incorrecto');
                document.getElementById('grupo__dni').classList.remove('formulario__grupo-correcto');
                document.getElementById('error-dni').style.display = "none";
            } else {
                if ($("#dni").val() !== "") {
                    if (reg_cedula.test($("#dni").val()) && cedulaValida($("#dni").val())) {
                        document.getElementById('grupo__dni').classList.remove('formulario__grupo-incorrecto');
                        document.getElementById('grupo__dni').classList.add('formulario__grupo-correcto');
                        document.querySelector('.formulario__validacion-dni').classList.remove('fa-circle-xmark');
                        document.querySelector('.formulario__validacion-dni').classList.add('fa-circle-check');
                        document.getElementById('error-dni').style.display = "none";
                    } else {
                        document.getElementById('grupo__dni').classList.remove('formulario__grupo-correcto');
                        document.getElementById('grupo__dni').classList.add('formulario__grupo-incorrecto');
                        document.querySelector('.formulario__validacion-dni').classList.remove('fa-circle-check');
                        document.querySelector('.formulario__validacion-dni').classList.add('fa-circle-xmark');
                        document.getElementById('error-dni').style.display = "block";
                    }
                }
            }
        })

        $("#paralelo").on('change', function() {
            let id_paralelo = $(this).val();

            if (id_paralelo == "") {
                $("#botones").fadeOut();
                $("#error-paralelo").fadeIn("slow");
                $("#lista_estudiantes").hide();
            } else {
                $("#botones").fadeIn("slow");
                $("#error-paralelo").fadeOut();
                listarEstudiantesParalelo(id_paralelo);
                $("#lista_estudiantes").show();
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
</script>