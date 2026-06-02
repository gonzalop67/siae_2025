@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1 class="h3 mb-4 text-gray-800">Lista de Tareas</h1>

            <?php $search = isset($_GET['search']) ? $_GET['search'] : ''; ?>

            <nav class="navbar navbar-expand navbar-light bg-light mb-4">
                <div class="container-fluid d-flex justify-content-between align-items-center w-100">
                    <div class="d-flex align-items-center">
                        <button type="button" id="btn-add" class="btn btn-primary btn-sm mr-1" data-toggle="modal"
                            data-target="#nuevaTareaModal">
                            <i class="fa-solid fa-plus"></i> Nueva Tarea
                        </button>
                        <a href="<?= RUTA_URL ?>/tasks/wastebasket" class="btn btn-danger btn-sm"><i
                                class="fa-solid fa-trash"></i> Papelera</a>
                    </div>
                    <form action="<?= RUTA_URL ?>/tasks" class="form-inline" role="search">
                        <input class="form-control form-control-sm mr-2" type="search" name="search"
                            value="{{ $search }}" placeholder="Buscar..." aria-label="Search">
                        <button class="btn btn-outline-primary btn-sm" type="submit">Buscar</button>
                    </form>
                </div>
            </nav>

            @if (count($tasks['data']) > 0)
                <div class="table-responsive-sm">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tarea</th>
                                <th>Hecho</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $contador = $tasks['from'] - 1; @endphp
                            @foreach ($tasks['data'] as $row)
                                @php $contador++; @endphp
                                <tr>
                                    <td>{{ $contador }}</td>
                                    @php
                                        $clase = $row['hecho'] ? 'taskDone' : '';
                                    @endphp
                                    <td>
                                        <!-- Agregamos la clase "task-text" como ancla para JavaScript -->
                                        <div class="task-text {{ $clase }}">
                                            {{ $row['tarea'] }}
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $checked = $row['hecho'] ? 'checked' : '';
                                        @endphp
                                        <input type='checkbox' onclick="checkTask(this,{{ $row['id'] }})"
                                            {{ $checked }}>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="#" onclick="obtenerDatos({{ $row['id'] }})"
                                                class="btn btn-success btn-sm" data-toggle="modal"
                                                data-target="#editarTareaModal" title="Editar"><i
                                                    class="fa-solid fa-pencil"></i></a>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="confirmarEliminacion({{ $row['id'] }})" title="Eliminar"><i
                                                    class="fa-solid fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @include('includes.pagination')
            @else
                <div class="text-center">Aún no se han registrado Tasks.</div>
            @endif
        </div>
    </div>
    <?php require_once RAIZ_PROYECTO . '/resources/views/admin/tasks/modalInsert.php'; ?>
    <?php require_once RAIZ_PROYECTO . '/resources/views/admin/tasks/modalUpdate.php'; ?>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#form_insert').submit(function(e) {
                e.preventDefault();
                insertarTarea();
            });

            $('#form_update').submit(function(e) {
                e.preventDefault();
                actualizarTarea();
            });
        });

        function checkTask(obj, id) {
            var done = obj.checked;
            $.ajax({
                type: "POST",
                url: "<?= RUTA_URL ?>/tasks/" + id + "/update_done",
                data: "id=" + id + "&done=" + done,
                success: function(r) {
                    if (r.ok) {
                        // 1. Sube al <tr> más cercano. 
                        // 2. Busca el <div> con la clase .task-text dentro de esa fila y altera su clase.
                        var taskDiv = $(obj).closest('tr').find('.task-text');

                        if (done) {
                            taskDiv.addClass('taskDone');
                        } else {
                            taskDiv.removeClass('taskDone');
                        }
                    } else if (r.mensaje) {
                        obj.checked = !done;
                        Swal.fire({
                            title: 'Error de Proceso',
                            text: r.mensaje,
                            icon: 'error'
                        });
                    }
                },
                error: function() {
                    obj.checked = !done;
                    Swal.fire({
                        title: 'Error de Red',
                        text: 'No se pudo conectar con el servidor.',
                        icon: 'error'
                    });
                }
            });
        }

        function insertarTarea() {
            let cont_errores = 0;
            let tarea = $("#tarea").val().trim();

            if (tarea == "") {
                $("#error-tarea").html("Debes ingresar la nueva tarea...");
                $("#error-tarea").fadeIn();
                cont_errores++;
            } else {
                $("#error-tarea").fadeOut();
            }

            if (cont_errores == 0) {
                $.ajax({
                    type: "POST",
                    url: "<?= RUTA_URL ?>/tasks",
                    data: {
                        tarea: tarea
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
                            $('#form_insert')[0].reset(); //limpiar formulario
                            $('#nuevaTareaModal').modal('hide');
                            window.location.reload();
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
        }

        function obtenerDatos(id) {
            $.ajax({
                url: "<?= RUTA_URL ?>/tasks/" + id + "/edit",
                type: "POST",
                data: "id=" + id,
                dataType: "json",
                success: function(r) {
                    console.log(r);
                    $("#id_task").val(r.id);
                    $("#tareau").val(r.tarea);
                }
            });
        }

        function actualizarTarea() {
            const cont_errores = 0;
            const id = $("#id_task").val();
            const tarea = $("#tareau").val().trim();

            if (tarea == "") {
                $("#error-tarea").html("Debes ingresar la tarea editada...");
                $("#error-tarea").fadeIn();
                cont_errores++;
            } else {
                $("#error-tarea").fadeOut();
            }

            if (cont_errores == 0) {
                $.ajax({
                    type: "POST",
                    url: "<?= RUTA_URL ?>/tasks/" + id + "/update",
                    data: {
                        id: id,
                        tarea: tarea
                    },
                    dataType: "json",
                    success: function(r) {
                        // console.log(r);
                        if (r.ok) {
                            Swal.fire({
                                title: '¡Completado!',
                                text: r.mensaje,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            $('#form_update')[0].reset(); //limpiar formulario
                            $('#editarTareaModal').modal('hide');
                            window.location.reload();
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
        }

        function confirmarEliminacion(idTarea) {
            // 1. Mostrar alerta de confirmación previa al borrado
            Swal.fire({
                title: '¿Estás seguro?',
                text: "La Tarea será enviada a la papelera.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                // 2. Si el usuario confirma, enviamos la petición vía Fetch (AJAX)
                if (result.isConfirmed) {
                    // Reemplaza esta URL por la ruta real que apunte a tu método destroy
                    fetch(`${base_url}/tasks/${idTarea}/delete`, {
                            method: 'POST', // O 'DELETE' según manejes tus rutas en PHP puro
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // 3. Alerta de éxito total
                                Swal.fire(
                                    '¡Eliminado!',
                                    data.message,
                                    'success'
                                ).then(() => {
                                    // Recargamos la página o removemos la fila de la tabla dinámicamente
                                    location.reload();
                                });
                            } else {
                                // Alerta en caso de error lógico
                                Swal.fire('Error', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            // Alerta en caso de error de red
                            Swal.fire('Error', 'No se pudo comunicar con el servidor.', 'error');
                        });
                }
            });
        }
    </script>
@endsection
