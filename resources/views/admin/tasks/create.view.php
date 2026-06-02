@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800">{{ $title }}</h1>

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fa-solid fa-square-plus"></i> Registrar Nuevo Record</h6>
                    <a href="{{ RUTA_URL }}/tasks" class="btn btn-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Volver</a>
                </div>
                <div class="card-body">
                    <form action="{{ RUTA_URL }}/tasks" method="POST" autocomplete="off">
                        <div class="row">
                            <!-- Campos Dinámicos -->
                            <div class="col-md-8 offset-md-2">
    <div class="form-group mb-3">
        <label>Tarea</label>
        <input type="text" name="tarea" class="form-control form-control-sm" required>
    </div>
    <div class="form-group mb-3">
        <label>Hecho</label>
        <input type="number" name="hecho" class="form-control form-control-sm" required>
    </div>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-end">
                            <a href="{{ RUTA_URL }}/tasks" class="btn btn-light btn-sm mr-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-floppy-disk"></i> Guardar Registro</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection