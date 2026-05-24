@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800">Lista de Usuarios</h1>
            
            <?php
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            ?>

            <nav class="navbar navbar-expand navbar-light bg-light mb-4">
                <div class="container-fluid d-flex justify-content-between align-items-center w-100">

                    <!-- Contenedor para los botones (Alineados a la izquierda) -->
                    <div class="d-flex align-items-center">
                        <?php // if (tiene_permiso('crear-usuario')):
                        ?>
                        <!-- Se cambió me-3 por mr-3 (Bootstrap 4) y se quitó el mb-3 para alinearlos bien -->
                        <a href="<?= RUTA_URL ?>/users/create" class="btn btn-primary btn-sm mr-1"><i
                                class="fa-solid fa-user-plus"></i> Nuevo Usuario</a>
                        <a href="<?= RUTA_URL ?>/users/wastebasket" class="btn btn-danger btn-sm"><i
                                class="fa-solid fa-trash"></i> Papelera</a>
                        <?php // endif;
                        ?>
                    </div>

                    <!-- Formulario de búsqueda (Alineado a la derecha) -->
                    <form action="<?= RUTA_URL ?>/users" class="form-inline" role="search">
                        <!-- Se cambió me-2 por mr-2 (Bootstrap 4) -->
                        <input class="form-control form-control-sm mr-2" type="search" name="search"
                            value="{{ $search }}" placeholder="Usuario a buscar..." aria-label="Search">
                        <button class="btn btn-outline-primary btn-sm" type="submit">Buscar</button>
                    </form>

                </div>
            </nav>

            @include('includes.message')

            @if (count($users) > 0)
                <div class="table-responsive-sm">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Avatar</th>
                                <th>Nombre de Usuario</th>
                                <th>Nombre Completo</th>
                                <th>Email</th>
                                <th class="text-center">Roles</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $contador = $users['from'] - 1;
                            @endphp
                            @foreach ($users['data'] as $user)
                                @php
                                    $contador++;
                                @endphp
                                <tr>
                                    <td>{{ $contador }}</td>
                                    @php
                                        // 1. Detectar el nombre de la foto o usar el fallback
                                        $fotoNombre = !empty($user['us_foto']) ? $user['us_foto'] : 'no-disponible.png';

                                        // 2. Definir la ruta física exacta basándonos en tu depuración exitosa
                                        $rutaFisica = dirname($_SERVER['SCRIPT_FILENAME']) . '/uploads/' . $fotoNombre;

                                        // 3. Validar si el archivo existe en el disco. Si no, usar la imagen por defecto
                                        if (!file_exists($rutaFisica)) {
                                            $fotoNombre = 'no-disponible.png';
                                        }

                                        /**
                                         * 4. CONSTRUIR URL WEB CORRECTA
                                         * Como tu proyecto corre bajo la carpeta public/, RUTA_URL ya incluye '/public'.
                                         * Por lo tanto, la carpeta de imágenes para el navegador es simplemente '/uploads/'.
                                         */
                                        $avatarUrl = RUTA_URL . '/public/uploads/' . $fotoNombre;
                                    @endphp

                                    <td>
                                        <img src="{{ $avatarUrl }}" style="border-radius: 50%" width="45"
                                            alt="Avatar del Usuario">
                                    </td>
                                    <td>{{ $user['us_login'] }}</td>
                                    <td>{{ $user['us_fullname'] }}</td>
                                    <td>{{ $user['us_email'] }}</td>
                                    <td class="text-center">
                                        <a href="{{ RUTA_URL }}/users/{{ $user['id_usuario'] }}/roles"
                                            class="btn btn-sm btn-primary" title="Roles">
                                            <i class="fa-solid fa-user-gear"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="{{ RUTA_URL }}/users/{{ $user['id_usuario'] }}/edit"
                                                type="button" class="btn btn-success btn-sm" title="Editar Usuario"><i
                                                    class="fa-solid fa-pencil"></i></a>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="confirmarEliminacion({{ $user['id_usuario'] }})"
                                                title="Eliminar Usuario">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <?php
                $paginate = 'users';
                ?>
                @include('assets.pagination')
            @else
                <div class="text-center">
                    Aún no se han registrado Usuarios.
                </div>
            @endif
        </div>
    </div>
    <script>
        function confirmarEliminacion(idUsuario) {
            // 1. Mostrar alerta de confirmación previa al borrado
            Swal.fire({
                title: '¿Estás seguro?',
                text: "El usuario será enviado a la papelera.",
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
                    fetch(`${base_url}/users/${idUsuario}/delete`, {
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
