<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Mantenimiento de Armas</h4>
        </div>
        <div class="card-body">
            <form id="FormArma">
                <input type="hidden" id="id_arma" name="id_arma">

                <div class="mb-3">
                    <label for="id_tipo_arma" class="form-label">Tipo de Arma</label>
                    <select id="id_tipo_arma" name="id_tipo_arma" class="form-select" required>
                        <option value="">Seleccione un tipo de arma...</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="id_usuario" class="form-label">Asignada A</label>
                    <select id="id_usuario" name="id_usuario" class="form-select" required>
                        <option value="">Seleccione un usuario...</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cantidad" class="form-label">Cantidad</label>
                        <input type="number" id="cantidad" name="cantidad" class="form-control" placeholder="Ingrese la cantidad" min="1" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="estado" class="form-label">Estado del Arma</label>
                        <input type="text" id="estado" name="estado" class="form-control" placeholder="Ej: Activa, En Bodega, Decomisada">
                    </div>
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
            <h4 class="mb-0">Listado de Armas Registradas</h4>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <button type="button" id="BtnNuevo" class="btn btn-success">
                    <i class="bi bi-plus-circle me-1"></i> Registrar Nueva Arma
                </button>
            </div>

            <div class="table-responsive">
                <table id="TableArmas" class="table table-striped table-hover table-bordered w-100"></table>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/arma/index.js') ?>"></script>