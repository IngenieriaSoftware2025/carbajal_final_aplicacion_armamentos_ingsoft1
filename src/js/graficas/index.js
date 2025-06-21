// build/js/graficas/index.js
import { Dropdown } from "bootstrap";
import Chart from "chart.js/auto";
import Swal from "sweetalert2";

console.log("Cargando gráficas...");

// Contextos de los tres canvas
const ctx1 = document.getElementById('miGrafico1').getContext('2d');
const ctx2 = document.getElementById('miGrafico2').getContext('2d');
const ctx3 = document.getElementById('miGrafico3').getContext('2d');

// Inicializamos los tres Chart.js vacíos
window.funcionGrafico1 = new Chart(ctx1, {
    type: 'bar',
    data: { labels: [], datasets: [] },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
    }
});
window.funcionGrafico2 = new Chart(ctx2, {
    type: 'pie',
    data: { labels: [], datasets: [] },
    options: { responsive: true }
});
window.funcionGrafico3 = new Chart(ctx3, {
    type: 'line',
    data: { labels: [], datasets: [] },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
    }
});

// Helpers
function getColorForEstado(c) {
    if (c > 15) return 'green';
    if (c === 15) return 'yellow';
    if (c >= 13) return 'orange';
    if (c === 13) return 'red';
    return 'gray';
}
function formatearFecha(fechaStr) {
    const d = new Date(fechaStr);
    return d.toLocaleDateString('es-GT', { month: 'short', day: 'numeric' });
}

// Carga los datos y pinta las gráficas
async function buscarDatos() {
    try {
        const resp = await fetch('/carbajal_final_aplicacion_armamentos_ingsoft1/graficas/datos');
        const json = await resp.json();

        // console.log('Datos completos de /graficas/datos:', json);
        // document.getElementById('muestraResumen').innerHTML =
        //     `<pre style="font-size:0.8em;">${JSON.stringify(json, null, 2)}</pre>`;

        if (json.codigo !== 1) {
            return Swal.fire('Error', json.mensaje, 'error');
        }

        const { armasPorTipo, historialPorUsuario, actividadReciente } = json.data;

        // Gráfico 1: Tipo Armamento
        if (armasPorTipo) {
            const labels = armasPorTipo.map(d => d.nombre_tipo);
            const data = armasPorTipo.map(d => +d.total_cantidad);
            funcionGrafico1.data.labels = labels;
            funcionGrafico1.data.datasets = [{
                label: 'Cantidad de Armas',
                data,
                backgroundColor: data.map(getColorForEstado),
                borderColor: data.map(getColorForEstado),
                borderWidth: 1
            }];
            funcionGrafico1.update();
        }

        // Gráfico 2: Historial de Usuarios
        if (historialPorUsuario) {
            const labels = historialPorUsuario.map(d => d.usuario);
            const data = historialPorUsuario.map(d => +d.total_acciones);
            const colors = labels.map((_, i) =>
                `hsl(${(i * 360 / labels.length).toFixed(0)},70%,60%)`
            );
            funcionGrafico2.data = {
                labels,
                datasets: [{
                    label: 'Acciones por Usuario',
                    data,
                    backgroundColor: colors
                }]
            };
            funcionGrafico2.update();
        }

        // Gráfico 3: Cantidad de Armas (línea)
        if (actividadReciente) {
            const labels = actividadReciente.map(d => formatearFecha(d.fecha));
            const data = actividadReciente.map(d => +d.total_acciones);
            funcionGrafico3.data.labels = labels;
            funcionGrafico3.data.datasets = [{
                label: 'Acciones en 30 días',
                data,
                borderColor: 'rgb(75,192,192)',
                backgroundColor: 'rgba(75,192,192,0.2)',
                tension: 0.4
            }];
            funcionGrafico3.update();
        }

    } catch (err) {
        console.error(err);
        Swal.fire('Error de conexión', 'No se pudieron cargar los datos', 'error');
    }
}

document.addEventListener('DOMContentLoaded', buscarDatos);
