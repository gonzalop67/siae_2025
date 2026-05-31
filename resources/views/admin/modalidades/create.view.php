<h1>{{ $title }}</h1>
<form action="/modalidades/store" method="POST">
    <div class="form-group mb-3">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" required>
    </div>
    <div class="form-group mb-3">
        <label>Activo</label>
        <input type="number" name="activo" class="form-control" required>
    </div>
    <div class="form-group mb-3">
        <label>Orden</label>
        <input type="number" name="orden" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="/modalidades" class="btn btn-secondary">Cancelar</a>
</form>