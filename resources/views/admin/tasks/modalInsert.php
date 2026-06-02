<!-- Nueva Tarea Modal -->
<div class="modal" id="nuevaTareaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva Tarea</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_insert" action="{{ RUTA_URL }}/tasks/store" method="POST">
                <div class="modal-body">
                    <div class="row mb-2">
                        <label for="tarea" class="col-sm-2 col-form-label">Tarea:</label>
                        <div class="col-sm-10">
                            <textarea id="tarea" name="tarea" class="form-control" cols="40" rows="5" placeholder="Inserte una nueva tarea..."></textarea>
                            <div id="error-tarea" class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Fin Nueva Tarea Modal -->