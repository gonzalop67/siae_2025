@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1 class="h3 mb-4 text-gray-800">Lista de Ofertas Educativas</h1>

            <?php $search = isset($_GET['search']) ? $_GET['search'] : ''; ?>

            <nav class="navbar navbar-expand navbar-light bg-light mb-4">
                <div class="container-fluid d-flex justify-content-between align-items-center w-100">
                    <div class="d-flex align-items-center">
                        <a href="<?= RUTA_URL ?>/ofertas_educativas/create" class="btn btn-primary btn-sm mr-1"><i class="fa-solid fa-plus"></i> Nueva Oferta Educativa</a>
                        <a href="<?= RUTA_URL ?>/ofertas_educativas/wastebasket" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Papelera</a>
                    </div>
                    <form action="<?= RUTA_URL ?>/ofertas_educativas" class="form-inline" role="search">
                        <input class="form-control form-control-sm mr-2" type="search" name="search" value="{{ $search }}" placeholder="Buscar..." aria-label="Search">
                        <button class="btn btn-outline-primary btn-sm" type="submit">Buscar</button>
                    </form>
                </div>
            </nav>

            @if (count($ofertas_educativas['data']) > 0)
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
                            @php $contador = $ofertas_educativas['from'] - 1; @endphp
                            @foreach ($ofertas_educativas['data'] as $reg)
                                @php $contador++; @endphp
                                <tr>
                                    <td>{{ $contador }}</td>
                                    <td>{{ $reg['nombre'] }}</td>
                                    <td>{{ $reg['activo'] }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ RUTA_URL }}/ofertas_educativas/{{ $reg['id'] }}/edit" class="btn btn-success btn-sm" title="Editar"><i class="fa-solid fa-pencil"></i></a>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminacion({{ $reg['id'] }})" title="Eliminar"><i class="fa-solid fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @include('includes.pagination')
            @else
                <div class="text-center">Aún no se han registrado Ofertas_educativas.</div>
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
                    fetch(`${base_url}/ofertas_educativas/${id}/delete`, {
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