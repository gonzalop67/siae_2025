@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1 class="h3 mb-4 text-gray-800">Papelera de Tasks</h1>

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
                                <th>Hecho</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $contador = $tasks['from'] - 1; @endphp
                            @foreach ($tasks['data'] as $reg)
                                @php $contador++; @endphp
                                <tr>
                                    <td>{{ $contador }}</td>
                                    <td>{{ $reg['tarea'] }}</td>
                                    <td>{{ $reg['hecho'] }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <form action="{{ RUTA_URL }}/tasks/{{ $reg['id'] }}/restore" method="POST" style="display:inline;">
                                                <button type="submit" class="btn btn-success btn-sm" title="Restaurar"><i class="fa-solid fa-rotate-left"></i></button>
                                            </form>
                                            <form action="{{ RUTA_URL }}/tasks/{{ $reg['id'] }}/destroy" method="POST" style="display:inline;" onclick="return confirm('¿Eliminar permanentemente de la base de datos?')">
                                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar Permanente"><i class="fa-solid fa-ban"></i></button>
                                            </form>
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
@endsection