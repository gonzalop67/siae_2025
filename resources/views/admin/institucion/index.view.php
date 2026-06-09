@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- Caja con borde superior info -->
            <div class="card shadow mb-4" style="border-top: 3px solid #17a2b8;">

                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold">Datos de la Institución Educativa</h6>
                </div>

                <div class="card-body">
                    <form id="form_institucion" action="" method="POST" class="form-horizontal"
                        enctype="multipart/form-data">
                        <!-- AGREGA ESTA LÍNEA DEBAJO DEL FORM -->
                        <input type="hidden" name="id"
                            value="{{ $institucion['id_institucion'] ?? '' }}">

                        <div class="form-group row align-items-center">
                            <!-- 1. Se cambia 'control-label text-right' por 'col-form-label text-md-right' -->
                            <label for="nombre"
                                class="col-sm-2 col-form-label text-md-right font-weight-bold">Nombre:</label>

                            <div class="col-sm-10">
                                <!-- 2. Mantienes tus atributos nativos e interactivos intactos -->
                                <input type="text" class="form-control" name="nombre" id="nombre"
                                    onfocus="sel_texto(this)" value="{{ $institucion['in_nombre'] ?? '' }}" required>

                                <!-- 3. Se añade 'invalid-feedback' o 'text-danger' para controlar el diseño del error -->
                                <span class="error text-danger small d-block mt-1" id="error-nombre"></span>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label for="direccion"
                                class="col-sm-2 col-form-label text-md-right font-weight-bold">Dirección:</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="direccion" id="direccion"
                                    value="{{ $institucion['in_direccion'] ?? '' }}" onfocus="sel_texto(this)" required>
                                <span class="error text-danger small d-block mt-1" id="error-direccion"></span>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label for="email"
                                class="col-sm-2 col-form-label text-md-right font-weight-bold">Email:</label>

                            <div class="col-sm-10">
                                <input type="email" class="form-control" name="email" id="email"
                                    value="{{ $institucion['in_email'] ?? '' }}" onfocus="sel_texto(this)" required>
                                <span class="error text-danger small d-block mt-1" id="error-email"></span>
                            </div>
                        </div>

                        <div id="administrador" class="form-group row align-items-start">
                            <!-- Usamos align-items-start por si el avatar se despliega -->

                            <!-- Primera Columna (2 de 12): Etiqueta Principal -->
                            <label for="admin_id"
                                class="col-sm-2 col-form-label text-md-right font-weight-bold requerido">Administrador:</label>

                            <!-- Segunda Columna (10 de 12): Contenedor de todo el bloque interactivo -->
                            <div class="col-sm-10">
                                <input type="hidden" name="id_usuario_admin" value="">

                                <!-- El Menú Desplegable (Ocupa el ancho completo de su columna en Bootstrap 4) -->
                                <select class="form-control" id="admin_id" name="admin_id">
                                    <option value="">Seleccione Administrador de la Institución Educativa...</option>
                                    @foreach ($admin_list as $row)
                                        <option value="{{ $row['id_usuario'] }}" data-foto="{{ $row['us_foto'] ?? '' }}"
                                            {{ $institucion['admin_id'] == $row['id_usuario'] ? 'selected' : '' }}>
                                            {{ $row['us_shortname'] }}
                                        </option>
                                    @endforeach
                                </select>

                                <!-- Bloque del Avatar: Se despliega elegantemente JUSTO ABAJO del select cuando se activa -->
                                <div id="img_admin" class="d-none mt-3">
                                    <img id="avatar_admin" name="avatar_admin" class="img-thumbnail shadow-sm"
                                        style="width: 75px; height: auto;" alt="Avatar del administrador">
                                </div>
                            </div>

                        </div>

                        <div class="form-group row align-items-center">
                            <label for="telefono"
                                class="col-sm-2 col-form-label text-md-right font-weight-bold">Teléfono:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="telefono" id="telefono"
                                    value="{{ $institucion['in_telefono'] ?? '' }}" onfocus="sel_texto(this)" required>
                                <span class="error text-danger small d-block mt-1" id="error-telefono"></span>
                            </div>
                            <label for="regimen"
                                class="col-sm-2 col-form-label text-md-right font-weight-bold">Régimen:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control text-uppercase" id="regimen"
                                    value="{{ $institucion['in_regimen'] ?? '' }}" onfocus="sel_texto(this)">
                                <span class="error text-danger small d-block mt-1" id="error-regimen"></span>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <!-- Primer Campo: Rector (a) -->
                            <label for="nom_rector" class="col-sm-2 col-form-label text-md-right font-weight-bold">Rector
                                (a):</label>
                            <div class="col-sm-4 mb-3 mb-md-0"> <!-- Margen inferior para vista móvil -->
                                <input type="text" class="form-control" name="nom_rector" id="nom_rector"
                                    value="{{ $institucion['in_nom_rector'] ?? '' }}" onfocus="sel_texto(this)" required>
                                <span class="error text-danger small d-block mt-1" id="error-nom_rector"></span>
                            </div>

                            <!-- Segundo Campo: Género (Corregido con col-form-label, alineación y peso visual) -->
                            <label for="genero_rector"
                                class="col-sm-2 col-form-label text-md-right font-weight-bold">Género:</label>
                            <div class="col-sm-4">
                                <!-- El select de Bootstrap 4 adopta automáticamente un diseño limpio y moderno con form-control -->
                                <select name="genero_rector" id="genero_rector" class="form-control">
                                    <option value="F"
                                        {{ $institucion['in_genero_rector'] ? ($institucion['in_genero_rector'] == 'F' ? 'selected' : '') : '' }}>
                                        Femenino</option>
                                    <option value="M"
                                        {{ $institucion['in_genero_rector'] ? ($institucion['in_genero_rector'] == 'M' ? 'selected' : '') : '' }}>
                                        Masculino</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label for="nom_vicerrector"
                                class="col-sm-2 col-form-label text-md-right font-weight-bold">Vicerrector (a):</label>
                            <div class="col-sm-4 mb-3 mb-md-0"> <!-- Margen inferior para vista móvil -->
                                <input type="text" class="form-control" name="nom_vicerrector" id="nom_vicerrector"
                                    value="{{ $institucion['in_nom_vicerrector'] ?? '' }}" onfocus="sel_texto(this)"
                                    required>
                                <span class="error text-danger small d-block mt-1" id="error-nom_vicerrector"></span>
                            </div>

                            <label for="genero_vicerrector"
                                class="col-sm-2 col-form-label text-md-right font-weight-bold">Género:</label>
                            <div class="col-sm-4">
                                <!-- El select de Bootstrap 4 adopta automáticamente un diseño limpio y moderno con form-control -->
                                <select name="genero_vicerrector" id="genero_vicerrector" class="form-control fuente10">
                                    <option value="F"
                                        {{ $institucion['in_genero_vicerrector'] ? ($institucion['in_genero_vicerrector'] == 'F' ? 'selected' : '') : '' }}>
                                        Femenino</option>
                                    <option value="M"
                                        {{ $institucion['in_genero_vicerrector'] ? ($institucion['in_genero_vicerrector'] == 'M' ? 'selected' : '') : '' }}>
                                        Masculino</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label for="nom_secretario"
                                class="col-sm-2 col-form-label text-md-right font-weight-bold">Secretario (a):</label>
                            <div class="col-sm-4 mb-3 mb-md-0"> <!-- Margen inferior para vista móvil -->
                                <input type="text" class="form-control" name="nom_secretario" id="nom_secretario"
                                    value="{{ $institucion['in_nom_secretario'] ?? '' }}" onfocus="sel_texto(this)"
                                    required>
                                <span class="error text-danger small d-block mt-1" id="error-nom_secretario"></span>
                            </div>

                            <label for="genero_secretario"
                                class="col-sm-2 col-form-label text-md-right font-weight-bold">Género:</label>
                            <div class="col-sm-4">
                                <!-- El select de Bootstrap 4 adopta automáticamente un diseño limpio y moderno con form-control -->
                                <select name="genero_secretario" id="genero_secretario" class="form-control fuente10">
                                    <option value="F"
                                        {{ $institucion['in_genero_secretario'] ? ($institucion['in_genero_secretario'] == 'F' ? 'selected' : '') : '' }}>
                                        Femenino</option>
                                    <option value="M"
                                        {{ $institucion['in_genero_secretario'] ? ($institucion['in_genero_secretario'] == 'M' ? 'selected' : '') : '' }}>
                                        Masculino</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label for="url"
                                class="col-sm-2 col-form-label text-md-right font-weight-bold">URL:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="url" id="url"
                                    value="{{ $institucion['in_url'] ?? '' }}" onfocus="sel_texto(this)" required>
                                <span class="error text-danger small d-block mt-1" id="error-url"></span>
                            </div>
                            <label for="amie"
                                class="col-sm-2 col-form-label text-md-right font-weight-bold">AMIE:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control text-uppercase" name="amie" id="amie"
                                    value="{{ $institucion['in_amie'] ?? '' }}" onfocus="sel_texto(this)">
                                <span class="error text-danger small d-block mt-1" id="error-amie"></span>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <label for="ciudad"
                                class="col-sm-2 col-form-label text-md-right font-weight-bold">Ciudad:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="ciudad" id="ciudad"
                                    value="{{ $institucion['in_ciudad'] ?? '' }}" onfocus="sel_texto(this)" required>
                                <span class="error text-danger small d-block mt-1" id="error-ciudad"></span>
                            </div>
                            <label for="copiar_y_pegar"
                                class="col-sm-2 col-form-label text-md-right font-weight-bold">Copy & Paste:</label>
                            <div class="col-md-4 d-flex align-items-center">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="copiar_y_pegar"
                                        name="copiar_y_pegar" {{ $institucion['in_copiar_y_pegar'] == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="copiar_y_pegar"></label>
                                </div>
                            </div>
                        </div>

                        <div id="img_upload">
                            <!-- Fila 1: Previsualización de la Imagen -->
                            <div class="form-group row align-items-center">
                                <label for="img_logo"
                                    class="col-sm-2 col-form-label text-md-right font-weight-bold">Imagen:</label>

                                <div id="img_div" class="col-sm-10 <?php echo empty($institucion['in_logo']) ? 'd-none' : ''; ?>">
                                    <!-- Se utiliza RUTA_URL correctamente para cargar el archivo guardado -->
                                    <img id="img_logo" name="img_logo" src="<?php echo !empty($institucion['in_logo']) ? RUTA_URL . '/public/uploads/' . $institucion['in_logo'] : ''; ?>"
                                        class="img-thumbnail shadow-sm" style="width: 75px; height: auto;"
                                        alt="Avatar de la institucion">
                                </div>
                            </div>

                            <!-- Fila 2: Selector de Archivo -->
                            <div class="form-group row align-items-center">
                                <label for="in_logo"
                                    class="col-sm-2 col-form-label text-md-right font-weight-bold">Archivo:</label>

                                <!-- Tu input oculto se mantiene para registrar el estado o nombre previo -->
                                <input type="hidden" name="in_logo_file" id="in_logo_file" value="<?php echo $institucion['in_logo'] ?? ''; ?>">

                                <div class="col-sm-10">
                                    <div class="custom-file">
                                        <!-- SE REMUEVE EL ATRIBUTO 'required' para permitir actualizaciones de solo texto -->
                                        <input type="file" class="custom-file-input" name="in_logo" id="in_logo"
                                            accept="image/png, image/jpeg, image/jpg, image/svg+xml">
                                        <label class="custom-file-label text-muted" for="in_logo"
                                            data-browse="Buscar">Seleccionar imagen...</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Se elimina el estilo en línea y se añade 'mt-2' (Margen superior nativo) -->
                            <div class="col-sm-12 mt-2">
                                <!-- 'shadow-sm' añade una sombra suave alineada con la estética de SB Admin 2 -->
                                <button id="btn-add-item" type="submit"
                                    class="btn btn-primary btn-block shadow-sm font-weight-bold">
                                    <!-- Opcional: Un ícono de Font Awesome le da un toque más moderno -->
                                    <i class="fas fa-save mr-2"></i> Actualizar datos de la Institución
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Loader Moderno con Spinner de Bootstrap 4 -->
                    <div id="img_loader" class="text-center d-none my-3">
                        <!-- Spinner circular de color azul primario -->
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Procesando...</span>
                        </div>
                    </div>

                    <!-- Contenedor para Mensajes o Alertas -->
                    <div id="mensaje" class="small text-center my-2"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ RUTA_URL }}/public/assets/js/funciones.js"></script>
    <script>
        $(document).ready(function() {
            // 1. Mostrar dinámicamente el nombre de la imagen seleccionada en el input
            $('#in_logo').on('change', function(e) {
                var fileName = e.target.files[0] ? e.target.files[0].name : "Seleccionar imagen...";
                $(this).next('.custom-file-label').text(fileName);

                // Previsualización local inmediata antes de enviar al servidor
                if (e.target.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        $('#img_logo').attr('src', event.target.result);
                        $('#img_div').removeClass('d-none');
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });

            // 2. Procesamiento AJAX del Formulario
            $('#form_institucion').on('submit', function(e) {
                e.preventDefault(); // Evita recargar la página

                // Limpieza de estados e interfaces previas
                $('.error').text(
                    ''
                ); // Limpia los pequeños textos de error (ej: <span class="error text-danger"></span>)
                $('.is-invalid').removeClass('is-invalid'); // Quita bordes rojos de Bootstrap si los usas
                $('#mensaje').removeClass('alert alert-success alert-danger').text('').addClass('d-none');
                $('#btn-submit').prop('disabled', true);
                $('#img_loader').removeClass('d-none');

                // Captura todos los campos (incluyendo el archivo binario)
                var formData = new FormData(this);

                // Verificamos si el checkbox está seleccionado en la interfaz
                var checkboxValor = $('#copiar_y_pegar').is(':checked') ? 1 : 0;

                // Reemplazamos o añadimos el valor correcto dentro del objeto FormData
                formData.set('copiar_y_pegar', checkboxValor);

                $.ajax({
                    type: "POST",
                    url: "{{ RUTA_URL }}/institucion/update",
                    data: formData,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#img_loader').addClass('d-none');
                        $('#btn-submit').prop('disabled', false);

                        if (response.success) {
                            // 1. Mostrar mensaje de éxito global
                            $('#mensaje')
                                .removeClass('d-none')
                                .addClass('alert alert-success')
                                .text(response.message);

                            // Optativo: Si subieron un logo nuevo y tienes una etiqueta <img> de previsualización, 
                            // puedes recargar la sección o limpiar el input file.
                            $('#in_logo').val('');

                        } else {
                            // 2. Mostrar mensaje de error global
                            $('#mensaje')
                                .removeClass('d-none')
                                .addClass('alert alert-danger')
                                .text(response.message);

                            // 3. Renderizar errores específicos de los campos
                            if (response.errors) {
                                $.each(response.errors, function(campo, mensajeError) {
                                    // CORRECCIÓN: Se cambia el guión bajo '_' por el guión medio '-'
                                    $('#error-' + campo).text(mensajeError);

                                    // Si usas Bootstrap, esto le pone el borde rojo al input automáticamente
                                    $('[name="' + campo + '"]').addClass('is-invalid');
                                    // Para el caso especial de tu input file si su name difiere
                                    if (campo === 'in_logo') {
                                        $('#in_logo').addClass('is-invalid');
                                    }
                                });
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        // Manejo de fallos críticos del servidor (Errores 500, 404, etc.)
                        $('#img_loader').addClass('d-none');
                        $('#btn-submit').prop('disabled', false);

                        $('#mensaje')
                            .removeClass('d-none')
                            .addClass('alert alert-danger')
                            .text(
                                'Ocurrió un error inesperado en el servidor. Por favor, inténtelo de nuevo.'
                            );

                        console.error("Detalles del error AJAX:", error, xhr.responseText);
                    }
                });
            });

            // Escuchar cuando el usuario seleccione un administrador diferente
            $('#admin_id').on('change', function() {
                // 1. Obtener la opción seleccionada
                var optionSeleccionada = $(this).find('option:selected');
                var idUsuario = $(this).val();

                // 2. Extraer el nombre de la imagen del atributo 'data-avatar'
                var nombreAvatar = optionSeleccionada.data('foto');

                // 3. Evaluar si se seleccionó un usuario válido y si tiene una foto asignada
                if (idUsuario !== "" && nombreAvatar !== undefined && nombreAvatar !== "") {

                    // Construimos la ruta absoluta usando la misma lógica que tu logotipo
                    // Nota: Si RUTA_URL no está disponible de forma global en JS, puedes inyectarla o usar rutas relativas '/public/uploads/'
                    var rutaCompletaAvatar = base_url + '/public/uploads/' + nombreAvatar;

                    // Actualizamos el src del elemento img
                    $('#avatar_admin').attr('src', rutaCompletaAvatar);

                    // Mostramos el contenedor removiendo d-none
                    $('#img_admin').removeClass('d-none');

                } else {
                    // Si elige la opción por defecto ("Seleccione..."), ocultamos el avatar
                    $('#img_admin').addClass('d-none');
                    $('#avatar_admin').attr('src', '');
                }
            });

            // =========================================================================
            // AGREGA ESTA LÍNEA AQUÍ ABAJO:
            // =========================================================================
            // Al cargar la vista, si el select ya viene con un ID pre-seleccionado por Blade,
            // forzamos a jQuery a disparar el evento 'change' inmediatamente para pintar el avatar.
            if ($('#admin_id').val() !== "") {
                $('#admin_id').trigger('change');
            }
        });
    </script>
@endsection
