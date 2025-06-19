<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Mantenimiento de Tipos de Armas</h4>
        </div>
        <div class="card-body">
            <form id="FormTipoArma">
                <input type="hidden" id="id_tipo_arma" name="id_tipo_arma">

                <div class="mb-3">
                    <label for="nombre_tipo" class="form-label">Nombre del Tipo de Arma</label>
                    <input type="text" id="nombre_tipo" name="nombre_tipo" class="form-control" placeholder="Ej: Pistola, Rifle, Escopeta" required>
                </div>

                <div class="mb-3">
                    <label for="calibre" class="form-label">Calibre</label>
                    <input type="text" id="calibre" name="calibre" class="form-control" placeholder="Ej: 9mm, .45, 12 Gauge">
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
            <h4 class="mb-0">Lista de Tipos de Armas</h4>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <button type="button" id="BtnNuevo" class="btn btn-success">
                    <i class="bi bi-plus-circle me-1"></i> Nuevo Tipo de Arma
                </button>
            </div>

            <div class="table-responsive">
                <table id="TableTiposArmas" class="table table-striped table-hover table-bordered w-100"></table>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/tipo/index.js') ?>"></script>