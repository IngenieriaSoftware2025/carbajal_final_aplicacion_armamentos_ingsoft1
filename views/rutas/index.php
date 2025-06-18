<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Mantenimiento de Rutas</h4>
        </div>
        <div class="card-body">
            <form id="FormRuta">
                <input type="hidden" id="id_ruta" name="id_ruta">

                <div class="mb-3">
                    <label for="id_app" class="form-label">Aplicación</label>
                    <select id="id_app" name="id_app" class="form-select">
                        <option value="">Seleccione una aplicación</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="ruta" class="form-label">Ruta</label>
                    <input type="text" id="ruta" name="ruta" class="form-control" placeholder="Ingrese la ruta">
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <input type="text" id="descripcion" name="descripcion" class="form-control" placeholder="Ingrese la descripción de la ruta">
                </div>

                <div class="d-flex justify-content-between">
                    <div>
                        <button type="submit" id="BtnGuardar" class="btn btn-success">
                            <i class="bi bi-save me-1"></i> Guardar
                        </button>
                        <button type="button" id="BtnModificar" class="btn btn-warning d-none">
                            <i class="bi bi-pencil me-1"></i> Modificar
                        </button>
                        <button type="reset" id="BtnLimpiar" class="btn btn-secondary">
                            <i class="bi bi-arrow-repeat me-1"></i> Limpiar
                        </button>
                    </div>
                    <div>
                        <button type="button" id="BtnVerLista" class="btn btn-info">
                            <i class="bi bi-table me-1"></i> Ver Lista
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container mt-4 d-none" id="seccionTabla">
    <div class="card">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">Lista de Rutas</h4>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <button type="button" id="BtnNuevo" class="btn btn-success">
                    <i class="bi bi-plus-circle me-1"></i> Nueva Ruta
                </button>
            </div>

            <div class="table-responsive">
                <table id="TableRutas" class="table table-striped table-hover table-bordered w-100"></table>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/rutas/rutas.js') ?>"></script>