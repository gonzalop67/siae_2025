    <!-- New Student Modal -->
    <div class="modal fade" id="newStudentModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Estudiante Nuevo</h4>
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                </div>

                <form id="formulario" action="" method="post" autocomplete="off">
                    <!-- Modal body -->
                    <div class="modal-body fuente9">
                        <div class="row mb-2">
                            <!-- Grupo: Tipo de Documento -->
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

                            <!-- Grupo: DNI -->
                            <label for="dni" class="col-sm-1 form-label">DNI:</label>
                            <div class="col-sm-5 formulario__grupo" id="grupo__dni">
                                <div class="formulario__grupo-input">
                                    <input type="text" class="form-control formulario__input fuente9" id="dni" name="dni" value="">
                                    <i id="icon__dni" class="formulario__validacion-estado fa-solid fa-circle-xmark"></i>
                                </div>
                                <span id="error-dni" class="invalid-feedback">Debe ingresar un número de cédula válido.</span>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <!-- Grupo: Apellidos -->
                            <label for="apellidos" class="col-sm-2 form-label">Apellidos:</label>
                            <div class="col-sm-10 formulario__grupo" id="grupo__apellidos">
                                <div class="formulario__grupo-input">
                                    <input type="text" class="form-control formulario__input text-uppercase fuente9" id="apellidos" name="apellidos" minlength="3" maxlength="32" value="">
                                    <i id="icon__apellidos" class="formulario__validacion-estado fa-solid fa-circle-xmark"></i>
                                    <span id="error-apellidos" class="invalid-feedback">Los apellidos del estudiante deben contener entre 4 y 32 caracteres alfabéticos y espacio entre apellidos.</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <!-- Grupo: Nombres -->
                            <label for="nombres" class="col-sm-2 form-label">Nombres:</label>
                            <div class="col-sm-10 formulario__grupo" id="grupo__nombres">
                                <div class="formulario__grupo-input">
                                    <input type="text" class="form-control formulario__input text-uppercase fuente9" id="nombres" name="nombres" value="">
                                    <i id="icon__nombres" class="formulario__validacion-estado fa-solid fa-circle-xmark"></i>
                                    <span id="error-nombres" class="invalid-feedback">Los nombres del estudiante deben contener entre 4 y 32 caracteres alfabéticos y espacio entre nombres.</span>
                                </div>
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
                            <!-- Grupo: Email -->
                            <label for="email" class="col-sm-2 form-label">E-mail:</label>
                            <div class="col-sm-10 formulario__grupo" id="grupo__email">
                                <div class="formulario__grupo-input">
                                    <input type="text" class="form-control formulario__input text-lowercase fuente9" id="email" name="email" value="">
                                    <i id="icon__email" class="formulario__validacion-estado fa-solid fa-circle-xmark"></i>
                                    <span id="error-email" class="invalid-feedback">Debe ingresar el correo electrónico en el formato válido.</span>
                                </div>
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
                                <span id="error-nacionalidad" class="invalid-feedback">Debe seleccionar la nacionalidad del estudiante.</span>
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