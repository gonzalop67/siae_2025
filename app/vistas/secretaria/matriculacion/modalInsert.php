    <!-- New Student Modal -->
    <!-- <div class="modal fade" id="newStudentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-center" id="myModalLabel1">Estudiante Nuevo</h4>
                </div>
                <form id="form_insert" action="" method="post" autocomplete="off">
                    <div class="modal-body fuente9">
                        <div class="form-group row">
                            <label for="new_id_tipo_documento" class="col-sm-2 col-form-label">Tipo de Documento:</label>
                            <div class="col-sm-4">
                                <select class="form-control fuente9" id="new_id_tipo_documento" name="new_id_tipo_documento" required>
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($datos['tipos_documento'] as $v) { ?>
                                        <option value="<?php echo $v->id_tipo_documento; ?>"><?php echo $v->td_nombre; ?></option>
                                    <?php } ?>
                                </select>
                                <span id="mensaje2" style="color: #e73d4a"></span>
                            </div>
                            <label for="new_dni" class="col-sm-1 col-form-label">DNI:</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="new_dni" name="new_dni" value="">
                                <span id="mensaje3" style="color: #e73d4a"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="new_apellidos" class="col-sm-2 col-form-label">Apellidos:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control mayusculas" id="new_apellidos" name="new_apellidos" value="">
                                <span id="mensaje4" style="color: #e73d4a"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="new_nombres" class="col-sm-2 col-form-label">Nombres:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control mayusculas" id="new_nombres" name="new_nombres" value="">
                                <span id="mensaje5" style="color: #e73d4a"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="new_fec_nac" class="col-sm-2 col-form-label">Fecha de nacimiento:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="new_fec_nac" name="new_fec_nac" value="" placeholder="aaaa-mm-dd" maxlength="10">
                                <span id="mensaje6" style="color: #e73d4a"></span>
                            </div>

                            <label for="new_edad" class="col-sm-1 col-form-label">Edad:</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="new_edad" name="new_edad" value="" disabled>
                                <span id="mensaje6" style="color: #e73d4a"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="new_direccion" class="col-sm-2 col-form-label">Dirección:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control mayusculas" id="new_direccion" name="new_direccion" value="">
                                <span id="mensaje7" style="color: #e73d4a"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="new_sector" class="col-sm-2 col-form-label">Sector:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control mayusculas" id="new_sector" name="new_sector" value="">
                                <span id="mensaje8" style="color: #e73d4a"></span>
                            </div>
                            <label for="new_telefono" class="col-sm-1 col-form-label">Celular:</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="new_telefono" name="new_telefono" value="">
                                <span id="mensaje9" style="color: #e73d4a"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="new_email" class="col-sm-2 col-form-label">E-mail:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="new_email" name="new_email" value="">
                                <span id="mensaje10" style="color: #e73d4a"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="new_genero" class="col-sm-2 col-form-label">Género:</label>
                            <div class="col-sm-4">
                                <select class="form-control fuente9" id="new_genero" name="new_genero">
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($datos['def_generos'] as $v) { ?>
                                        <option value="<?php echo $v->id_def_genero; ?>"><?php echo $v->dg_nombre; ?></option>
                                    <?php } ?>
                                </select>
                                <span id="mensaje11" style="color: #e73d4a"></span>
                            </div>
                            <label for="new_nacionalidad" class="col-sm-2 col-form-label">Nacionalidad:</label>
                            <div class="col-sm-4">
                                <select class="form-control fuente9" id="new_nacionalidad" name="new_nacionalidad">
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($datos['def_nacionalidades'] as $v) { ?>
                                        <option value="<?php echo $v->id_def_nacionalidad; ?>"><?php echo $v->dn_nombre; ?></option>
                                    <?php } ?>
                                </select>
                                <span id="mensaje12" style="color: #e73d4a"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancelar</button>
                        <button type="button" class="btn btn-success" onclick="insertarEstudiante()"><span class="glyphicon glyphicon-save"></span> Insertar</a>
                    </div>
                </form>
            </div>
        </div>
    </div> -->

    <div class="modal fade" id="newStudentModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Estudiante Nuevo</h4>
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                </div>

                <form id="form_insert" action="" method="post" autocomplete="off">
                    <!-- Modal body -->
                    <div class="modal-body fuente9">
                        <div class="row mb-2">
                            <label for="tipo_documento" class="col-sm-2 form-label">Tipo de Documento:</label>
                            <div class="col-sm-4">
                                <select class="form-control fuente9" id="tipo_documento" name="tipo_documento" required>
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($datos['tipos_documento'] as $v) { ?>
                                        <option value="<?php echo $v->id_tipo_documento; ?>"><?php echo $v->td_nombre; ?></option>
                                    <?php } ?>
                                </select>
                                <span id="error-tipo_documento" class="invalid-feedback">Debe seleccionar un tipo de documento.</span>
                            </div>
                            <label for="dni" class="col-sm-1 form-label">DNI:</label>
                            <div class="col-sm-5">
                                <div class="formulario__grupo-input" id="grupo__dni">
                                    <input type="text" class="form-control formulario__input fuente9" id="dni" name="dni" value="">
                                    <i class="formulario__validacion-dni fa-solid fa-circle-xmark"></i>
                                </div>
                                <span id="error-dni" class="invalid-feedback">Debe ingresar un número de cédula válido.</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="apellidos" class="col-sm-2 form-label">Apellidos:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control text-uppercase fuente9" id="apellidos" name="apellidos" value="">
                                <span id="error-apellidos" class="invalid-feedback">Debe ingresar los apellidos del estudiante.</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="nombres" class="col-sm-2 form-label">Nombres:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control text-uppercase fuente9" id="nombres" name="nombres" value="">
                                <span id="error-nombres" class="invalid-feedback">Debe ingresar los nombres del estudiante.</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="fec_nac" class="col-sm-2 form-label">Fecha de nacimiento:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control fuente9" id="fec_nac" name="fec_nac" value="" placeholder="aaaa-mm-dd" maxlength="10">
                                <span id="error-fec_nac" class="invalid-feedback">Debe ingresar la fecha de nacimiento en el formato indicado.</span>
                            </div>

                            <label class="col-sm-1 form-label">Edad:</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control fuente9" id="edad" value="" disabled>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="direccion" class="col-sm-2 form-label">Dirección:</label>
                            <div class="col-sm-10">
                                <textarea class="form-control text-uppercase fuente9" name="direccion" id="direccion" rows="2"></textarea>
                                <span id="error-direccion" class="invalid-feedback">Debe ingresar la dirección del estudiante.</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="sector" class="col-sm-2 form-label">Sector:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control text-uppercase fuente9" id="sector" name="sector" value="">
                                <span id="error-sector" class="invalid-feedback">Debe ingresar el sector de la dirección del estudiante.</span>
                            </div>
                            <label for="telefono" class="col-sm-1 form-label">Telf:</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control fuente9" id="telefono" name="telefono" value="">
                                <span id="error-telefono" class="invalid-feedback">Debe ingresar el número de celular del estudiante.</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="email" class="col-sm-2 form-label">E-mail:</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control text-lowercase fuente9" id="email" name="email" value="">
                                <span id="error-email" class="invalid-feedback">Debe ingresar el correo electrónico en el formato válido.</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="genero" class="col-sm-2 form-label">Género:</label>
                            <div class="col-sm-4">
                                <select class="form-control fuente9" id="genero" name="genero">
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($datos['def_generos'] as $v) { ?>
                                        <option value="<?php echo $v->id_def_genero; ?>"><?php echo $v->dg_nombre; ?></option>
                                    <?php } ?>
                                </select>
                                <span id="error-genero" class="invalid-feedback">Debe seleccionar el género del estudiante.</span>
                            </div>
                            <label for="nacionalidad" class="col-sm-2 form-label">Nacionalidad:</label>
                            <div class="col-sm-4">
                                <select class="form-control fuente9" id="nacionalidad" name="nacionalidad">
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($datos['def_nacionalidades'] as $v) { ?>
                                        <option value="<?php echo $v->id_def_nacionalidad; ?>"><?php echo $v->dn_nombre; ?></option>
                                    <?php } ?>
                                </select>
                                <span id="error-genero" class="invalid-feedback">Debe seleccionar la nacionalidad del estudiante.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="insertarEstudiante()">Insertar</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>
        </div>
    </div>