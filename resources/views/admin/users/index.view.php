@extends('layout.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Lista de Usuarios</h1>
        <?php
        $search = isset($_GET['search']) ? $_GET['search'] : "";
        ?>

        <nav class="navbar bg-light">
            <div class="container-fluid">
                <?php //if (tiene_permiso('crear-usuario')): 
                ?>
                <a href="<?= RUTA_URL ?>/users/create" class="navbar-brand btn btn-primary btn-sm mb-3"><i class="fa-solid fa-user-plus"></i> Nuevo Usuario</a>
                <?php //endif; 
                ?>

                <form action="<?= RUTA_URL ?>/users" class="d-flex" role="search">
                    <input class="form-control me-2" type="search" name="search" value="{{ $search }}" placeholder="Usuario a buscar..." aria-label="Search">
                    <button class="btn btn-outline-primary ml-2" type="submit">Buscar</button>
                </form>
            </div>
        </nav>

        @include('includes.message')

        @if(count($users) > 0)
        <div class="table-responsive-sm">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Avatar</th>
                        <th>Nombre de Usuario</th>
                        <th>Nombre Abreviado</th>
                        <th>Email</th>
                        <th class="text-center">Roles</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $contador = $users['from'] - 1;
                    @endphp
                    @foreach($users['data'] as $user)
                    @php
                    $contador++;
                    @endphp
                    <tr>
                        <td>{{ $contador }}</td>
                        @php
                        // 1. Detectar el nombre de la foto o usar el fallback
                        $fotoNombre = !empty($user['us_foto']) ? $user['us_foto'] : 'no-disponible.png';

                        // 2. Definir la ruta física exacta basándonos en tu depuración exitosa
                        $rutaFisica = dirname($_SERVER['SCRIPT_FILENAME']) . "/uploads/" . $fotoNombre;

                        // 3. Validar si el archivo existe en el disco. Si no, usar la imagen por defecto
                        if (!file_exists($rutaFisica)) {
                        $fotoNombre = "no-disponible.png";
                        }

                        /**
                        * 4. CONSTRUIR URL WEB CORRECTA
                        * Como tu proyecto corre bajo la carpeta public/, RUTA_URL ya incluye '/public'.
                        * Por lo tanto, la carpeta de imágenes para el navegador es simplemente '/uploads/'.
                        */
                        $avatarUrl = RUTA_URL . "/public/uploads/" . $fotoNombre;
                        @endphp

                        <td>
                            <img src="{{ $avatarUrl }}" style="border-radius: 50%" width="45" alt="Avatar del Usuario">
                        </td>
                        <td>{{ $user['us_login'] }}</td>
                        <td>{{ $user['us_shortname'] }}</td>
                        <td>{{ $user['us_email'] }}</td>
                        <td class="text-center">
                            <a href="{{ RUTA_URL }}/users/{{ $user['id_usuario'] }}/roles" class="btn btn-sm btn-primary" title="Roles">
                                <i class="fa-solid fa-user-gear"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="#" type="button" class="btn btn-warning btn-sm" title="Mostrar Usuario"><i class="fa-solid fa-eye"></i></a>
                                <a href="#" type="button" class="btn btn-success btn-sm" title="Editar Usuario"><i class="fa-solid fa-pencil"></i></a>
                                <button type="submit" class="btn btn-danger btn-sm item-delete" data-id="{{ $user['id_usuario'] }}" title="Eliminar Usuario"><i class="fa-solid fa-trash"></i></button>
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
@endsection