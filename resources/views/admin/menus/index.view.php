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
                <a href="<?= RUTA_URL ?>/menus/create" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-plus"></i> Nuevo Menú
                </a>
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
@endsection

@section('scripts')
    <script src="{{ RUTA_URL }}/public/assets/js/jquery.nestable.js"></script>
    <script>
        $(document).ready(function() {
            $('#select-perfil').change(function() {
                let perfilId = $(this).val();

                if (perfilId === '') {
                    $('#nestable').html(
                        '<div class="dd-empty text-muted text-center py-4">Selecciona un perfil para cargar sus menús.</div>'
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
        });
    </script>
@endsection
