<div class="row justify-content-center p-3">
    <div class="col-lg-12">
        <!-- Header -->
        <div class="card custom-card shadow-lg mb-4" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <div class="row mb-3">
                    <h5 class="text-center mb-2">Graficas de sistema Armamento</h5>
                    <h4 class="text-center mb-2 text-primary">Bienvenido</h4>
                </div>

                <!-- Resumen -->
                <div class="row" id="muestraResumen">

                </div>
            </div>
        </div>

        <!-- Primera fila de gráficos -->
        <div class="row mb-4">
            <!-- Gráfico 1 - Productos más vendidos (Barras) -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-bar-chart"></i> Tipo Armamento
                        </h6>
                    </div>
                    <div class="card-body">
                        <canvas id="miGrafico1" height="100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráfico 2 - Distribución de productos (Pie) -->
            <div class="col-lg-3 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-pie-chart"></i> Historial de Usuarios
                        </h6>
                    </div>
                    <div class="card-body">
                        <canvas id="miGrafico2"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segunda fila de gráficos -->
        <div class="row mb-4">
            <!-- Gráfico 3 - Ventas por fecha (Línea) -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0">
                            <i class="bi bi-graph-up"></i> Cantidad de Armas
                        </h6>
                    </div>
                    <div class="card-body">
                        <canvas id="miGrafico3" height="80"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráfico 5 - Clientes con más productos (Doughnut) -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-person-check"></i> Estado de Armas
                        </h6>
                    </div>
                    <div class="card-body">
                        <canvas id="miGrafico4"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="<?= asset('build/js/graficas/index.js') ?>"></script>