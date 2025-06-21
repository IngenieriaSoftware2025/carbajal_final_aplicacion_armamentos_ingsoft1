<div class="row justify-content-center p-3">
    <div class="col-lg-12">
        <!-- Header -->
        <div class="card custom-card shadow-lg mb-4" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3 text-center">
                <h5>Gráficas de sistema Armamento</h5>
                <h4 class="text-primary">Bienvenido</h4>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-6 mb-4">
                <!-- Gráfico 1: Tipo Armamento -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="bi bi-bar-chart"></i> Tipo Armamento</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="miGrafico1" height="100"></canvas>
                    </div>
                </div>

                <!-- Gráfico 3: Cantidad de Armas -->
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0"><i class="bi bi-graph-up"></i> Cantidad de Armas (últimos 30 días)</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="miGrafico3" height="80"></canvas>
                    </div>
                </div>
            </div>

            <!-- Columna derecha: Historial de Usuarios -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="bi bi-pie-chart"></i> Historial de Usuarios</h6>
                    </div>
                    <div class="card-body d-flex align-items-center">
                        <canvas id="miGrafico2" height="200" style="flex:1;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/graficas/index.js') ?>"></script>