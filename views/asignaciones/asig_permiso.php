<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h4><i class="bi bi-person-badge-fill me-2"></i> Registrar Asignación de Permiso</h4>
        </div>
        <div class="card-body">
            <div class="mb-3 text-center">
                <h5>¡Bienvenido al registro, modificación y eliminación de asignaciones de permisos!</h5>
                <h4 class="text-primary">Gestión de Asignaciones</h4>
            </div>

            <div id="seccionFormulario">
                <form id="FormAsigPermisos">
                    <h4 class="mb-3 text-center text-primary" id="tituloFormulario">Registrar Asignación de Permiso</h4>
                    <input type="hidden" id="id_asig_permiso" name="id_asig_permiso">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="id_usuario" class="form-label">Usuario</label>
                            <select id="id_usuario" name="id_usuario" class="form-select" required>
                                <option value="">-- Selecciona un usuario --</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="id_permiso_app" class="form-label">Permiso / Aplicación</label>
                            <select id="id_permiso_app" name="id_permiso_app" class="form-select" required>
                                <option value="">-- Selecciona un permiso --</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="motivo" class="form-label">Motivo de Asignación</label>
                        <textarea class="form-control" id="motivo" name="motivo" rows="3" placeholder="Ingrese el motivo..."></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div>
                            <button type="submit" class="btn btn-success" id="BtnGuardar">
                                <i class="bi bi-save me-1"></i> Guardar
                            </button>
                            <button type="button" class="btn btn-warning d-none" id="BtnModificar">
                                <i class="bi bi-pencil-square me-1"></i> Modificar
                            </button>
                            <button type="button" class="btn btn-secondary" id="BtnLimpiar">
                                <i class="bi bi-arrow-clockwise me-1"></i> Limpiar
                            </button>
                        </div>

                        <div>
                            <button type="button" class="btn btn-info" id="BtnVerAsignaciones">
                                <i class="bi bi-table me-1"></i> Ver Asignaciones
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <div class="card shadow mt-4 d-none" id="seccionTabla">
        <div class="card-header bg-info text-white text-center">
            <h4><i class="bi bi-table me-2"></i> Asignaciones Registradas</h4>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <button type="button" class="btn btn-success" id="BtnCrearAsignacion">
                    <i class="bi bi-person-plus me-1"></i> Nueva Asignación
                </button>
                <button type="button" class="btn btn-outline-primary" id="BtnActualizarTabla">
                    <i class="bi bi-arrow-clockwise me-1"></i> Actualizar Lista
                </button>
            </div>

            <div class="table-responsive">
                <table id="TableAsignaciones" class="table table-striped table-bordered w-100"></table>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/asignaciones/asig_permisos.js') ?>"></script>