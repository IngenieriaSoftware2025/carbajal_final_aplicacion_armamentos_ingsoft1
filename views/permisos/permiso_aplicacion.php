<div class="container py-4">

    <!-- SECCIÓN DEL FORMULARIO -->
    <div id="seccionFormulario" class="card shadow mb-4">
        <div class="card-header bg-primary text-white text-center">
            <h4><i class="bi bi-link-45deg me-2"></i> Asignar Permisos a Aplicaciones</h4>
        </div>
        <div class="card-body">
            <form id="FormPermisoAplicacion">
                <input type="hidden" id="id_permiso_app" name="id_permiso_app">

                <div class="mb-3">
                    <label for="id_permiso" class="form-label">Permiso</label>
                    <select id="id_permiso" name="id_permiso" class="form-select" required></select>
                </div>

                <div class="mb-3">
                    <label for="id_app" class="form-label">Aplicación</label>
                    <select id="id_app" name="id_app" class="form-select" required></select>
                </div>

                <div class="d-flex justify-content-between">
                    <div>
                        <button class="btn btn-success" type="submit" id="BtnGuardar"><i class="bi bi-save me-1"></i> Guardar</button>
                    </div>
                    <div>
                        <button type="button" id="BtnVerTabla" class="btn btn-info"><i class="bi bi-list-ul me-1"></i> Ver Relaciones Registradas</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- SECCIÓN DE LA TABLA -->
    <div id="seccionTabla" class="card shadow d-none">
        <div class="card-header bg-info text-white text-center">
            <h4><i class="bi bi-list-ul me-2"></i> Relaciones Registradas</h4>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <button type="button" id="BtnCrearNuevaRelacion" class="btn btn-success"><i class="bi bi-link-45deg me-1"></i> Nueva Relación</button>
                <button type="button" id="BtnActualizarTabla" class="btn btn-outline-primary"><i class="bi bi-arrow-clockwise me-1"></i> Actualizar Lista</button>
            </div>
            <table id="TablePermisoAplicacion" class="table table-striped table-bordered w-100"></table>
        </div>
    </div>

</div>

<script src="<?= asset('build/js/permisos/permiso_aplicacion.js') ?>"></script>