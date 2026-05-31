@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1 class="h3 mb-4 text-gray-800">Lista de Modalidades</h1>

            <?php $search = isset($_GET['search']) ? $_GET['search'] : ''; ?>

            <nav class="navbar navbar-expand navbar-light bg-light mb-4">
                <div class="container-fluid d-flex justify-content-between align-items-center w-100">
                    <div class="d-flex align-items-center">
                        <a href="<?= RUTA_URL ?>/modalidades/create" class="btn btn-primary btn-sm mr-1"><i class="fa-solid fa-plus"></i> Nueva Modalidad</a>
                        <a href="<?= RUTA_URL ?>/modalidades/wastebasket" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Papelera</a>
                    </div>
                    <form action="<?= RUTA_URL ?>/modalidades" class="form-inline" role="search">
                        <input class="form-control form-control-sm mr-2" type="search" name="search" value="{{ $search }}" placeholder="Buscar..." aria-label="Search">
                        <button class="btn btn-outline-primary btn-sm" type="submit">Buscar</button>
                    </form>
                </div>
            </nav>

            @if (count($modalidades['data']) > 0)
                <div class="table-responsive-sm">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Activo</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $contador = $modalidades['from'] - 1; @endphp
                            @foreach ($modalidades['data'] as $reg)
                                @php $contador++; @endphp
                                <tr>
                                    <td>{{ $contador }}</td>
                                    <td>{{ $reg['nombre'] }}</td>
                                    <td>{{ $reg['activo'] }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ RUTA_URL }}/modalidades/{{ $reg['id_modalidad'] }}/edit" class="btn btn-success btn-sm" title="Editar"><i class="fa-solid fa-pencil"></i></a>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminacion({{ $reg['id_modalidad'] }})" title="Eliminar"><i class="fa-solid fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @include('includes.pagination')
            @else
                <div class="text-center">Aún no se han registrado Modalidades.</div>
            @endif
        </div>
    </div>
    <script>
        function confirmarEliminacion(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'El registro será enviado a la papelera.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, enviar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`${base_url}/modalidades/${id}/delete`, {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('¡Eliminado!', data.message, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Error', data.message || 'No se pudo eliminar', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endsection