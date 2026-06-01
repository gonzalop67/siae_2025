@extends('layout.app')

@section('styles')
    <link rel="stylesheet" href="{{ RUTA_URL }}/public/assets/css/jquery.nestable.css">
@endsection

@section('content')
    <div class="row justify-content-center">
        <!-- Columna única de tamaño 8 -->
        <div class="col-md-8">

            <!-- Cabecera y botón nuevo -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Lista de Menús</h1>
                <button type="button" id="btn-add" class="btn btn-primary btn-sm" data-toggle="modal"
                    data-target="#nuevoMenuModal">
                    <i class="fa-solid fa-plus"></i> Nuevo Menú
                </button>
            </div>

            <!-- Card contenedora para organizar los elementos -->
            <div class="card shadow mb-4">
                <div class="card-body">

                    <!-- Selector de Perfiles -->
                    <div class="form-group mb-4">
                        <label for="select-perfil" class="font-weight-bold">Selecciona un Perfil:</label>
                        <select id="select-perfil" class="form-control">
                            <option value="">-- Seleccione un Perfil --</option>
                            @foreach ($perfiles as $perfil)
                                <option value="{{ $perfil['id_perfil'] }}">{{ $perfil['pe_nombre'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Contenedor Nestable -->
                    <div class="cf nestable-lists">
                        <div id="nestable">
                            <div class="text-muted text-center py-4">
                                Selecciona un perfil para cargar sus menús.
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <?php require_once RAIZ_PROYECTO . '/resources/views/admin/menus/modalInsert.php'; ?>
    <?php require_once RAIZ_PROYECTO . '/resources/views/admin/menus/modalUpdate.php'; ?>
@endsection

@section('scripts')
    <script src="{{ RUTA_URL }}/public/assets/js/jquery.nestable.js"></script>
    <script src="{{ RUTA_URL }}/public/assets/js/funciones.js"></script>
    <script>
        $(document).ready(function() {
            $('#select-perfil').change(function() {
                let perfilId = $(this).val();

                if (perfilId === '') {
                    $('#nestable').html(
                        '<div class="text-muted text-center py-4">Selecciona un perfil para cargar sus menús.</div>'
                    );
                    return;
                }

                $('#nestable').html(
                    '<div class="text-muted text-center py-4"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Cargando menús...</div>'
                );

                $.ajax({
                    url: '<?= RUTA_URL ?>/menus/get_menu_ajax', // Ajusta esta ruta a tu enrutador de Laravel/PHP
                    type: 'POST',
                    data: {
                        perfil_id: perfilId
                    },
                    success: function(htmlResponse) {
                        $('#nestable').html(htmlResponse);

                        // Reinicializar Nestable
                        $('#nestable').removeData('nestable');
                        $('#nestable').nestable({});
                    },
                    error: function() {
                        $('#nestable').html(
                            '<div class="dd-empty text-danger text-center py-4">Error al cargar los menús.</div>'
                        );
                    }
                });

            });

            $('#form_update').submit(function(e) {
                e.preventDefault();
                actualizarMenu();
            });
        });

        function obtenerDatos(id) {
            $.ajax({
                url: "<?= RUTA_URL ?>/menus/" + id + "/edit",
                type: "POST",
                data: "id=" + id,
                dataType: "json",
                success: function(r) {
                    // console.log(r);
                    $("#id_menu").val(r.id_menu);
                    $("#textou").val(r.mnu_texto);
                    $("#enlaceu").val(r.mnu_link);
                    $("#iconou").val(r.mnu_icono);
                    setearIndice("publicadou", r.mnu_publicado);
                }
            });
        }

        function actualizarMenu() {
            const cont_errores = 0;
            const id = $("#id_menu").val();
            const texto = $("#textou").val().trim();
            const enlace = $("#enlaceu").val().trim();
            const icono = $("#iconou").val().trim();
            const publicado = $("#publicadou").val();
            const id_perfil = $("#select-perfil").val();

            var reg_texto = /^([a-zA-Z ñáéíóúÑÁÉÍÓÚ]{3,64})$/i;

            if (texto == "") {
                $("#error-textou").html("Debe ingresar el texto del menú...");
                $("#error-textou").fadeIn();
                cont_errores++;
            } else if (!reg_texto.test(texto)) {
                $("#error-textou").html("El texto del menú debe contener al menos tres caracteres alfabéticos.");
                $("#error-textou").fadeIn();
                cont_errores++;
            } else {
                $("#error-textou").fadeOut();
            }

            if (enlace == "") {
                $("#error-enlaceu").html("Debe ingresar el enlace del menú...");
                $("#error-enlaceu").fadeIn();
                cont_errores++;
            } else {
                $("#error-enlaceu").fadeOut();
            }

            if (cont_errores == 0) {
                $('#button-update').html("<i class='fa-solid fa-spinner fa-spin mr-2'></i> Actualizando menú...");

                $.ajax({
                    url: "<?= RUTA_URL ?>/menus/" + id + "/update",
                    type: "POST",
                    data: {
                        id_menu: id,
                        mnu_texto: texto,
                        mnu_link: enlace,
                        mnu_icono: icono,
                        mnu_publicado: publicado,
                        id_perfil: id_perfil
                    },
                    dataType: "json",
                    success: function(r) {
                        if (r.ok) {
                            Swal.fire({
                                title: '¡Completado!',
                                text: r.mensaje,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            $('#form_update')[0].reset(); //limpiar formulario
                            $('#editarMenuModal').modal('hide');
                            $('#button-update').html("<i class='fa fa-pencil'></i> Actualizar");
                        } else if (r.errors) {
                            Object.keys(json.errors).forEach((campo) => {
                                const mensajeError = json.errors[campo];
                                const errorContainer = document.getElementById(`error-${campo}`);
                                const elementosByName = document.getElementsByName(campo);
                                let elemento = (elementosByName.length > 0) ? elementosByName[0] :
                                    document.getElementById(campo);

                                if (errorContainer) {
                                    errorContainer.innerHTML = mensajeError;
                                    errorContainer.style.display = 'block';
                                }

                                if (elemento) {
                                    elemento.classList.remove('is-valid');
                                    elemento.classList.add('is-invalid');
                                }
                            });

                            Swal.fire({
                                title: 'Error de Validación',
                                text: 'Por favor, corrige los campos remarcados en rojo.',
                                icon: 'error'
                            });
                        } else if (r.mensaje) {
                            Swal.fire({
                                title: 'Error de Proceso',
                                text: r.mensaje,
                                icon: 'error'
                            });
                        }

                    }
                });

            }

            return false;
        }
    </script>
@endsection
