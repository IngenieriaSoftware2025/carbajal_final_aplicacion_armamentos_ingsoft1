<!-- SECCIÓN DEL FORMULARIO (Vista 1) -->
<div id="seccionFormulario" class="row justify-content-center p-3">
    <div class="col-lg-10">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">
                    <i class="bi bi-shield-lock me-2"></i>
                    <span id="tituloFormulario">Registrar Permiso</span>
                </h4>
            </div>
            <div class="card-body p-3">
                <div class="row mb-3">
                    <h5 class="text-center mb-2">¡Bienvenido a la Aplicación para el registro, modificación y eliminación de permisos!</h5>
                    <h4 class="text-center mb-2 text-primary">Manipulación de permisos</h4>
                </div>

                <div class="row justify-content-center p-5 shadow-lg">
                    <form id="FormPermisos">
                        <input type="hidden" id="id_permiso" name="id_permiso">

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="nombre_permiso" class="form-label">Nombre del Permiso</label>
                                <input type="text" class="form-control" id="nombre_permiso" name="nombre_permiso" placeholder="Ingrese el nombre del permiso">
                            </div>
                            <div class="col-lg-6">
                                <label for="clave_permiso" class="form-label">Clave del Permiso</label>
                                <input type="text" class="form-control text-uppercase" id="clave_permiso" name="clave_permiso" placeholder="Ingrese la clave del permiso">
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center mb-3">
                            <div class="col-lg-12">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Describe las funcionalidades de este permiso..."></textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- BOTONES DEL FORMULARIO - MODIFICADOS IGUAL QUE USUARIOS -->
                <div class="row justify-content-between mt-4 px-5">
                    <!-- Botones de acción del formulario -->
                    <div class="col-auto">
                        <button class="btn btn-success" type="submit" form="FormPermisos" id="BtnGuardar">
                            <i class="bi bi-shield-plus me-1"></i> Guardar
                        </button>
                        <button class="btn btn-warning d-none" type="button" id="BtnModificar">
                            <i class="bi bi-pen me-1"></i> Modificar
                        </button>
                        <button class="btn btn-secondary ms-2" type="reset" id="BtnLimpiar">
                            <i class="bi bi-arrow-clockwise me-1"></i> Limpiar
                        </button>
                    </div>

                    <!-- NUEVO BOTÓN PARA VER PERMISOS -->
                    <div class="col-auto">
                        <button type="button" id="BtnVerPermisos" class="btn btn-info">
                            <i class="bi bi-shield-fill me-1"></i> Ver Permisos Registrados
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
                    <i class="bi bi-shield-fill me-2"></i>
                    Permisos Registrados
                </h4>
            </div>
            <div class="card-body p-3">

                <!-- BOTONES DE LA TABLA -->
                <div class="row justify-content-between mb-3">
                    <!-- BOTÓN PARA CREAR NUEVO -->
                    <div class="col-auto">
                        <button type="button" id="BtnCrearPermiso" class="btn btn-success">
                            <i class="bi bi-shield-plus me-1"></i> Crear Nuevo Permiso
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
                    <table class="table table-striped table-hover table-bordered w-100 table-sm" id="TablePermisos">
                        <!-- DataTable se genera automáticamente -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/permisos/permisos.js') ?>"></script>