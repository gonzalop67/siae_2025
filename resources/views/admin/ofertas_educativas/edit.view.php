<h1>{{ $title }}</h1>
<form action="/ofertas_educativas/update/{{ $data['id'] }}" method="POST">
    <div class="form-group mb-3">
        <label>Nombre</label>
        <input type="text" name="nombre" value="{{ $data['nombre'] }}" class="form-control form-control-sm" required>
    </div>
    <div class="form-group mb-3">
        <label>Activo</label>
        <input type="number" name="activo" value="{{ $data['activo'] }}" class="form-control" required>
    </div>
    <div class="form-group mb-3">
        <label>Orden</label>
        <input type="number" name="orden" value="{{ $data['orden'] }}" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="/ofertas_educativas" class="btn btn-secondary">Cancelar</a>
</form>