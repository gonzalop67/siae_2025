<!-- Editar Menu Modal -->
<div class="modal" id="editarMenuModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Menú</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_update" autocomplete="off">
                <input type="hidden" name="id_menu" id="id_menu" value="">
                <div class="modal-body">
                    <div class="row mb-2">
                        <label for="textou" class="col-sm-2 col-form-label">Texto:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="textou" id="textou" value=""
                                placeholder="" required>
                            <div id="error-textou" class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="enlaceu" class="col-sm-2 col-form-label">Enlace:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="enlaceu" id="enlaceu" value=""
                                placeholder="" required>
                            <div id="error-enlaceu" class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="iconou" class="col-sm-2 col-form-label">Icono:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="iconou" id="iconou" value=""
                                placeholder="" required>
                            <div id="error-iconou" class="invalid-feedback"></div>
                        </div>
                        <div class="col-sm-1">
                            <span id="mostrar-iconou" class="fas fa-fw "></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="publicadou" class="col-sm-2 col-form-label">Publicado:</label>
                        <div class="col-sm-10">
                            <select class="form-control fuente9" id="publicadou" name="publicadou">
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                            <div id="error-publicadou" class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" id="button-update" class="btn btn-success"><i class="fa fa-pencil"></i> Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Fin Editar Modalidad Modal -->