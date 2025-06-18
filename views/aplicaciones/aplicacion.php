<!-- SECCIÓN DEL FORMULARIO (Vista 1) -->
<div id="seccionFormulario" class="row justify-content-center p-3">
    <div class="col-lg-10">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">
                    <i class="bi bi-gear-fill me-2"></i>
                    <span id="tituloFormulario">Registrar Aplicación</span>
                </h4>
            </div>
            <div class="card-body p-3">
                <div class="row mb-3">
                    <h5 class="text-center mb-2">¡Bienvenido al Sistema de Gestión de Aplicaciones!</h5>
                    <h4 class="text-center mb-2 text-primary">Administración de Aplicaciones</h4>
                </div>

                <div class="row justify-content-center p-5 shadow-lg">
                    <form id="FormAplicacion">
                        <input type="hidden" id="id_app" name="id_app">

                        <!-- Nombre Largo de la Aplicación -->
                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-12">
                                <label for="nombre_app_lg" class="form-label">
                                    <i class="bi bi-card-text me-1"></i>Nombre Completo de la Aplicación
                                </label>
                                <input type="text" class="form-control" id="nombre_app_lg" name="nombre_app_lg"
                                    placeholder="Ej: Sistema Integral de Gestión Empresarial Corporativo"
                                    maxlength="2056">
                                <small class="text-muted">Descripción completa y detallada (10-2056 caracteres)</small>
                            </div>
                        </div>

                        <!-- Nombre Mediano -->
                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-8">
                                <label for="nombre_app_md" class="form-label">
                                    <i class="bi bi-card-heading me-1"></i>Nombre Mediano
                                </label>
                                <input type="text" class="form-control" id="nombre_app_md" name="nombre_app_md"
                                    placeholder="Ej: Sistema de Gestión Empresarial"
                                    maxlength="1056">
                                <small class="text-muted">Nombre abreviado (5-1056 caracteres)</small>
                            </div>
                        </div>

                        <!-- Siglas/Nombre Corto -->
                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-4">
                                <label for="nombre_app_ct" class="form-label">
                                    <i class="bi bi-tag-fill me-1"></i>Siglas/Código
                                </label>
                                <input type="text" class="form-control text-uppercase" id="nombre_app_ct" name="nombre_app_ct"
                                    placeholder="Ej: SGE"
                                    maxlength="255"
                                    style="font-weight: bold; letter-spacing: 1px;">
                                <small class="text-muted">Siglas únicas (2-255 caracteres)</small>
                            </div>
                        </div>

                        <!-- Información adicional (solo visual) -->
                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Información:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>La fecha de creación se asigna automáticamente</li>
                                        <li>Las siglas deben ser únicas en el sistema</li>
                                        <li>El nombre completo debe ser descriptivo</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Términos y condiciones -->
                        <div class="row mb-3 justify-content-center">
                            <div class="col-12 text-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="aceptarTerminos" required>
                                    <label class="form-check-label" for="aceptarTerminos">
                                        Confirmo que la información es correcta y acepto los
                                        <a href="#!" data-bs-toggle="modal" data-bs-target="#modalTerminos">Términos del Sistema</a>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- BOTONES DEL FORMULARIO -->
                <div class="row justify-content-between mt-4 px-5">
                    <!-- Botones de acción del formulario -->
                    <div class="col-auto">
                        <button class="btn btn-success" type="submit" form="FormAplicacion" id="BtnGuardar">
                            <i class="bi bi-gear-fill me-1"></i> Guardar Aplicación
                        </button>
                        <button class="btn btn-warning d-none" type="button" id="BtnModificar">
                            <i class="bi bi-pen me-1"></i> Modificar
                        </button>
                        <button class="btn btn-secondary ms-2" type="reset" id="BtnLimpiar">
                            <i class="bi bi-arrow-clockwise me-1"></i> Limpiar
                        </button>
                    </div>

                    <!-- NUEVO BOTÓN PARA VER APLICACIONES -->
                    <div class="col-auto">
                        <button type="button" id="BtnVerAplicaciones" class="btn btn-info">
                            <i class="bi bi-grid-3x3-gap-fill me-1"></i> Ver Aplicaciones Registradas
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
                    <i class="bi bi-grid-3x3-gap-fill me-2"></i>
                    Aplicaciones Registradas
                </h4>
            </div>
            <div class="card-body p-3">

                <!-- BOTONES DE LA TABLA -->
                <div class="row justify-content-between mb-3">
                    <!-- 🆕 BOTÓN PARA CREAR NUEVA -->
                    <div class="col-auto">
                        <button type="button" id="BtnCrearAplicacion" class="btn btn-success">
                            <i class="bi bi-gear-fill me-1"></i> Crear Nueva Aplicación
                        </button>
                    </div>

                    <!-- BOTÓN PARA ACTUALIZAR -->
                    <div class="col-auto">
                        <button type="button" id="BtnActualizarTabla" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-clockwise me-1"></i> Actualizar Lista
                        </button>
                    </div>
                </div>

                <!-- TABLA DE APLICACIONES -->
                <div class="table-responsive p-2">
                    <table class="table table-striped table-hover table-bordered w-100 table-sm" id="TableAplicaciones">
                        <!-- DataTable se genera automáticamente -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DE TÉRMINOS -->
<div class="modal fade" id="modalTerminos" tabindex="-1" aria-labelledby="modalTerminosLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTerminosLabel">
                    <i class="bi bi-shield-check me-2"></i>Términos del Sistema
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Condiciones para el registro de aplicaciones:</h6>
                <ul>
                    <li>La información debe ser veraz y actualizada</li>
                    <li>Las siglas deben ser únicas y representativas</li>
                    <li>No se permiten nombres duplicados</li>
                    <li>El sistema registrará automáticamente la fecha de creación</li>
                    <li>Los cambios quedan registrados en el sistema</li>
                </ul>
                <p class="text-muted small">
                    Al aceptar estos términos, confirma que tiene autorización para registrar esta aplicación en el sistema.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/aplicaciones/aplicacion.js') ?>"></script>