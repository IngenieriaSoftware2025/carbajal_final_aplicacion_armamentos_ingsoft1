<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="fas fa-shield-alt me-2"></i>
                Auditoría del Sistema
            </h4>
        </div>
        <div class="card-body">
            <!-- Estadísticas rápidas -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h5 id="totalRegistros">-</h5>
                            <small>Total Registros</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h5 id="usuariosUnicos">-</h5>
                            <small>Usuarios Únicos</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h5 id="ultimaActividad">-</h5>
                            <small>Última Actividad</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-secondary text-white">
                        <div class="card-body text-center">
                            <h5 id="estadoGeneral">-</h5>
                            <small>Estado General</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla principal -->
            <div class="table-responsive">
                <table id="TableAuditoria" class="table table-striped table-hover table-bordered w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Ruta</th>
                            <th>Ejecución</th>
                            <th>Status</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Se llena dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/auditoria/dashboard.js') ?>"></script>