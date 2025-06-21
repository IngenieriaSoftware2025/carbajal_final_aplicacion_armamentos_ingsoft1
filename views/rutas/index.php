<div class="container mt-4" id="seccionTabla">
    <div class="card">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">Lista de Rutas</h4>
        </div>
        <div class="card-body">
            <!-- Botón Volver -->
            <button type="button" id="BtnVolver" class="btn btn-secondary mb-3">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </button>

            <div class="table-responsive">
                <table id="TableRutas"
                    class="table table-striped table-hover table-bordered w-100">
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/rutas/rutas.js') ?>"></script>