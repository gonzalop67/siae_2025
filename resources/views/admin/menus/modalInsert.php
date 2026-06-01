<!-- Nuevo Menu Modal -->
<div class="modal" id="nuevoMenuModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Menú</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formulario" action="" method="post">
                <div class="modal-body">
                    <div class="row mb-2">
                        <label for="texto" class="col-sm-2 col-form-label">Texto:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="texto" id="texto" value=""
                                placeholder="" required>
                            <div id="error-texto" class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="enlace" class="col-sm-2 col-form-label">Enlace:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="enlace" id="enlace" value=""
                                placeholder="" required>
                            <div id="error-enlace" class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="icono" class="col-sm-2 col-form-label">Icono:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="icono" id="icono" value=""
                                placeholder="" required>
                            <div id="error-icono" class="invalid-feedback"></div>
                        </div>
                        <div class="col-sm-1">
                            <span id="mostrar-icono" class="fas fa-fw "></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Fin Nueva Modalidad Modal -->