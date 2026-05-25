@extends('layout.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5><strong>Nuevo Usuario</strong></h5>
                    <div>
                        <a href="{{ RUTA_URL }}/users">Volver al Listado de Usuarios</a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="formulario" action="" enctype="multipart/form-data" method="post">
                        <div class="row mb-2">
                            <label for="us_titulo" class="col-sm-2 col-form-label">Título (Abrev.):</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="us_titulo" id="us_titulo" value="" placeholder="Abreviatura del Título e.g. Lic." required autofocus>
                                <div id="error-us_titulo" class="invalid-feedback" style="display:none;"></div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="us_titulo_descripcion" class="col-sm-2 col-form-label">Descripción del Título:</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="us_titulo_descripcion" id="us_titulo_descripcion" rows="2" placeholder="Descripción del Título e.g. Licenciado en Ciencias de la Educación"></textarea>
                                <div id="error-us_titulo_descripcion" class="invalid-feedback" style="display:none;"></div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="us_apellidos" class="col-sm-2 col-form-label">Apellidos:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="us_apellidos" id="us_apellidos" value="" required>
                                <div id="error-us_apellidos" class="invalid-feedback" style="display:none;"></div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="us_nombres" class="col-sm-2 col-form-label">Nombres:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="us_nombres" id="us_nombres" value="" required>
                                <div id="error-us_nombres" class="invalid-feedback" style="display:none;"></div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="us_shortname" class="col-sm-2 col-form-label">Nombre Corto:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="us_shortname" id="us_shortname" value="" required>
                                <div id="error-us_shortname" class="invalid-feedback" style="display:none;"></div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="us_fullname" class="col-sm-2 col-form-label">Nombre Completo:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="us_fullname" id="us_fullname" value="" disabled>
                                <div id="error-us_fullname" class="invalid-feedback" style="display:none;"></div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="us_login" class="col-sm-2 control-label">Nombre de Usuario:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="us_login" id="us_login" value="" required>
                                <div id="error-us_login" class="invalid-feedback" style="display:none;"></div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="us_email" class="col-sm-2 control-label">Email:</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" name="us_email" id="us_email" value="" required>
                                <div id="error-us_email" class="invalid-feedback" style="display:none;"></div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="us_password" class="col-sm-2 control-label">Password:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="us_password" id="us_password" value="" required>
                                <div id="error-us_password" class="invalid-feedback" style="display:none;"></div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="us_genero" class="col-sm-2 control-label">Género:</label>
                            <div class="col-sm-10">
                                <select name="us_genero" id="us_genero" class="form-control">
                                    <option value="F">Femenino</option>
                                    <option value="M">Masculino</option>
                                </select>
                                <div id="error-us_genero" class="invalid-feedback" style="display:none;"></div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="us_activo" class="col-sm-2 control-label">Activo:</label>
                            <div class="col-sm-10">
                                <select name="us_activo" id="us_activo" class="form-control">
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                                <div id="error-us_activo" class="invalid-feedback" style="display:none;"></div>
                            </div>
                        </div>
                        <div id="perfiles-container" class="row mb-2">
                            <label for="perfiles" class="col-sm-2 control-label">Perfil:</label>
                            <div class="col-sm-10">
                                @foreach ($roles as $role)
                                <div>
                                    <input type="checkbox" name="perfiles[]" value="{{ $role['id_perfil'] }}">
                                    {{ $role['pe_nombre'] }}
                                </div>
                                @endforeach

                                <!-- Bloque donde se inyectará el mensaje de error de JS -->
                                <div id="error-perfiles" class="text-danger mt-1" style="display: none; font-size: 0.875em;"></div>
                            </div>

                        </div>
                        <div class="row mb-2">
                            <label for="us_avatar" class="col-sm-2 control-label"></label>

                            <div id="img_div" class="col-sm-10">
                                <img id="us_avatar" src="{{ RUTA_URL }}/public/assets/img/vecteezy_blue-profile-icon_36885313.png" name="us_avatar" class="img-thumbnail" width="75" alt="Avatar del usuario">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="us_foto" class="col-sm-2 control-label" style="margin-top: -4px;">Imagen:</label>

                            <div class="col-sm-10">
                                <input type="file" name="us_foto" id="us_foto">
                                <div id="error-us_foto" class="invalid-feedback" style="display:none;"></div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-2">
                            </div>
                            <div class="col-sm-10">
                                <button id="btn-save" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- <div class="card-footer text-muted">
                    2 days ago
                </div> -->
            </div>
        </div>
    </div>
</div>
<script src="{{ RUTA_URL }}/public/assets/js/pages/admin/usuarios/crear.js"></script>
@endsection