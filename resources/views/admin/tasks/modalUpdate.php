<!-- Editar Tarea Modal -->
<div class="modal" id="editarTareaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Tarea</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_update" action="{{ RUTA_URL }}/tasks/{{ $task['id'] }}" method="POST">
                <input type="hidden" name="id_task" id="id_task" value="">
                <div class="modal-body">
                    <div class="row mb-2">
                        <label for="tareau" class="col-sm-2 col-form-label">Tarea:</label>
                        <div class="col-sm-10">
                            <textarea id="tareau" name="tareau" class="form-control" cols="40" rows="5" placeholder="Edite su tarea..."></textarea>
                            <div id="error-tarea" class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-pencil"></i> Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Fin Editar Tarea Modal -->