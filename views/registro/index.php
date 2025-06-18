<!-- SECCIÓN DEL FORMULARIO (Vista 1) -->
<div id="seccionFormulario" class="row justify-content-center p-3">
    <div class="col-lg-10">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">
                    <i class="bi bi-person-plus-fill me-2"></i>
                    <span id="tituloFormulario">Registrar Usuario</span>
                </h4>
            </div>
            <div class="card-body p-3">
                <div class="row mb-3">
                    <h5 class="text-center mb-2">¡Bienvenido a la Aplicación para el registro, modificación y eliminación de usuarios!</h5>
                    <h4 class="text-center mb-2 text-primary">Manipulación de usuarios</h4>
                </div>

                <div class="row justify-content-center p-5 shadow-lg">
                    <form id="FormRegistro">
                        <input type="hidden" id="id_usuario" name="id_usuario">

                        <!-- Nombres -->
                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="nombre1" class="form-label">Primer Nombre</label>
                                <input type="text" class="form-control" id="nombre1" name="nombre1" placeholder="Ingrese su primer nombre">
                            </div>
                            <div class="col-lg-6">
                                <label for="nombre2" class="form-label">Segundo Nombre</label>
                                <input type="text" class="form-control" id="nombre2" name="nombre2" placeholder="Ingrese su segundo nombre">
                            </div>
                        </div>

                        <!-- Apellidos -->
                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="apellido1" class="form-label">Primer Apellido</label>
                                <input type="text" class="form-control" id="apellido1" name="apellido1" placeholder="Ingrese su primer apellido">
                            </div>
                            <div class="col-lg-6">
                                <label for="apellido2" class="form-label">Segundo Apellido</label>
                                <input type="text" class="form-control" id="apellido2" name="apellido2" placeholder="Ingrese su segundo apellido">
                            </div>
                        </div>

                        <!-- Información de contacto -->
                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="number" class="form-control" id="telefono" name="telefono" placeholder="Ingrese su número de teléfono">
                            </div>
                            <div class="col-lg-6">
                                <label for="dpi" class="form-label">DPI</label>
                                <input type="number" class="form-control" id="dpi" name="dpi" placeholder="Ingrese su número de DPI">
                            </div>
                        </div>

                        <!-- Correo -->
                        <div class="row mb-3 justify-content-center mb-3">
                            <div class="col-lg-6">
                                <label for="correo" class="form-label">Correo</label>
                                <input type="email" class="form-control" id="correo" name="correo" placeholder="ejemplo@ejemplo.com">
                            </div>
                        </div>

                        <!-- Contraseñas -->
                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="usuario_clave" class="form-label">Contraseña</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="usuario_clave" name="usuario_clave" placeholder="Contraseña segura">
                                    <button class="btn btn-outline-secondary" type="button" id="contraseniaBtn">
                                        <i class="bi bi-eye" id="iconOjo"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="confirmar_clave" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="confirmar_clave" name="confirmar_clave" placeholder="Confirme la contraseña">
                            </div>
                        </div>

                        <!-- Fotografía -->
                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="fotografia" class="form-label">
                                    <i class="bi bi-camera-fill me-1"></i>Fotografía del Usuario
                                </label>
                                <input type="file" class="form-control" id="fotografia" name="fotografia" accept="image/*">
                                <small class="text-muted">Formatos: JPG, PNG, GIF (Máx. 5MB)</small>
                            </div>
                        </div>

                        <!-- CONTENEDOR DE VISTA PREVIA -->
                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <div id="contenedorVistaPrevia" class="d-none">
                                    <label class="form-label">Vista Previa:</label>
                                    <div class="text-center p-3 border rounded" style="background-color: #f8f9fa;">
                                        <img id="vistaPrevia" src="" alt="Vista previa de la imagen"
                                            class="img-fluid rounded shadow"
                                            style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                        <br>
                                        <small class="text-muted mt-2 d-block" id="infoArchivo"></small>
                                        <button type="button" class="btn btn-sm btn-outline-danger mt-2" id="btnEliminarImagen">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

                <!-- BOTONES DEL FORMULARIO - MODIFICADOS -->
                <div class="row justify-content-between mt-4 px-5">
                    <!-- Botones de acción del formulario -->
                    <div class="col-auto">
                        <button class="btn btn-success" type="submit" form="FormRegistro" id="BtnGuardar">
                            <i class="bi bi-person-fill-add me-1"></i> Guardar
                        </button>
                        <button class="btn btn-warning d-none" type="button" id="BtnModificar">
                            <i class="bi bi-pen me-1"></i> Modificar
                        </button>
                        <button class="btn btn-secondary ms-2" type="reset" id="BtnLimpiar">
                            <i class="bi bi-arrow-clockwise me-1"></i> Limpiar
                        </button>
                    </div>

                    <!-- NUEVO BOTÓN PARA VER USUARIOS -->
                    <div class="col-auto">
                        <button type="button" id="BtnVerUsuarios" class="btn btn-info">
                            <i class="bi bi-people-fill me-1"></i> Ver Usuarios Registrados
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SECCIÓN DE LA TABLA (Vista 2) - OCULTA POR DEFECTO -->
<div id="seccionTabla" class="row justify-content-center p-3 d-none">
    <div class="col-lg-10">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #17a2b8;">
            <div class="card-header bg-info text-white text-center">
                <h4 class="mb-0">
                    <i class="bi bi-people-fill me-2"></i>
                    Usuarios Registrados
                </h4>
            </div>
            <div class="card-body p-3">

                <!-- BOTONES DE LA TABLA -->
                <div class="row justify-content-between mb-3">
                    <!-- BOTÓN PARA CREAR NUEVO -->
                    <div class="col-auto">
                        <button type="button" id="BtnCrearUsuario" class="btn btn-success">
                            <i class="bi bi-person-plus-fill me-1"></i> Crear Nuevo Usuario
                        </button>
                    </div>

                    <!-- BOTÓN PARA ACTUALIZAR -->
                    <div class="col-auto">
                        <button type="button" id="BtnActualizarTabla" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-clockwise me-1"></i> Actualizar Lista
                        </button>
                    </div>
                </div>

                <!-- TABLA EXISTENTE -->
                <div class="table-responsive p-2">
                    <table class="table table-striped table-hover table-bordered w-100 table-sm" id="TableUsuarios">
                        <!-- DataTable se genera automáticamente -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/registro/index.js') ?>"></script>