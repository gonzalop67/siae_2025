<h1>{{ $title }}</h1>
<form action="/tasks/update/{{ $data['id'] }}" method="POST">
    <div class="form-group mb-3">
        <label>Tarea</label>
        <input type="text" name="tarea" value="{{ $data['tarea'] }}" class="form-control form-control-sm" required>
    </div>
    <div class="form-group mb-3">
        <label>Hecho</label>
        <input type="number" name="hecho" value="{{ $data['hecho'] }}" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="/tasks" class="btn btn-secondary">Cancelar</a>
</form>