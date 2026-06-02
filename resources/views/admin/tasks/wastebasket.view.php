@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1 class="h3 mb-4 text-gray-800">Papelera de Tareas</h1>

            <div class="mb-4">
                <a href="<?= RUTA_URL ?>/tasks" class="btn btn-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Volver al Listado</a>
            </div>

            @if (count($tasks['data']) > 0)
                <div class="table-responsive-sm">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tarea</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $contador = $tasks['from'] - 1; @endphp
                            @foreach ($tasks['data'] as $row)
                                @php $contador++; @endphp
                                <tr>
                                    <td>{{ $contador }}</td>
                                    <td>{{ $row['tarea'] }}</td>
                                    <!-- Acciones de la Papelera: Restaurar y Eliminar Definitivamente -->
                                    <td class="text-center">
                                        <div class="btn-group" role="group" aria-label="Acciones Papelera">
                                            <!-- Botón Restaurar -->
                                            <button type="button" class="btn btn-success btn-sm"
                                                onclick="confirmarRestauracion({{ $row['id'] }})"
                                                title="Restaurar Usuario">
                                                <i class="fa-solid fa-rotate-left"></i>
                                            </button>
                                            <!-- Botón Eliminar Permanentemente -->
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="confirmarEliminacionDefinitiva({{ $row['id'] }})"
                                                title="Eliminar Permanentemente">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @include('includes.pagination')
            @else
                <div class="text-center">La papelera está vacía.</div>
            @endif
        </div>
    </div>

    <!-- Scripts de Confirmación con SweetAlert2 -->
    <script>
        // 1. Función para Restaurar el Usuario
        function confirmarRestauracion(idTarea) {
            Swal.fire({
                title: '¿Restaurar usuario?',
                text: "El usuario volverá a estar activo en la lista principal.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, restaurar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`${base_url}/tasks/${idTarea}/restore`, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('¡Restaurado!', 'La Tarea ha sido activada.', 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Error', 'No se pudo restaurar la tarea.', 'error');
                            }
                        });
                }
            });
        }

        // 2. Función para Eliminar Permanentemente de la Base de Datos
        function confirmarEliminacionDefinitiva(idTarea) {
            Swal.fire({
                title: '¿Eliminar permanentemente?',
                text: "Esta acción no se puede deshacer de ninguna manera.",
                icon: 'warning', // 'danger' no es un icono válido en SweetAlert2, se usa 'warning' o 'error'
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar definitivamente',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`${base_url}/tasks/${idTarea}/destroy`, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('¡Eliminado!', 'La Tarea ha sido borrada para siempre.', 'success')
                                    .then(() => location.reload());
                            } else {
                                // Muestra el mensaje específico enviado desde el controlador (Restricción de integridad)
                                Swal.fire({
                                    title: 'No se puede eliminar',
                                    text: data.mensaje,
                                    icon: 'error'
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error', 'Ocurrió un fallo en la comunicación con el servidor.', 'error');
                        });
                }
            });
        }
    </script>
@endsection