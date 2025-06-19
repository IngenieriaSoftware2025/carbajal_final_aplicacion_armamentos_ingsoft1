import { Dropdown } from "bootstrap";
import Chart from "chart.js/auto";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";
import { data } from "jquery";

const miGrafico1 = document.getElementById('miGrafico1').getContext('2d');
const miGrafico2 = document.getElementById('miGrafico2').getContext('2d');
const miGrafico3 = document.getElementById('miGrafico3').getContext('2d');
const miGrafico4 = document.getElementById('miGrafico4').getContext('2d');

// Gráfico 1 - Productos más vendidos (Barras)
window.funcionGrafico1 = new Chart(miGrafico1, {
    type: 'bar',
    data: {
        labels: [],
        datasets: []
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Gráfico 2 - Distribución de productos (Pie)
window.funcionGrafico2 = new Chart(miGrafico2, {
    type: 'pie',
    data: {
        labels: [],
        datasets: []
    },
    options: {
        responsive: true
    }
});

// Gráfico 3 - Ventas por fecha (Línea)
window.funcionGrafico3 = new Chart(miGrafico3, {
    type: 'line',
    data: {
        labels: [],
        datasets: []
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Gráfico 4 - Ventas por mes (Barras)
window.funcionGrafico4 = new Chart(miGrafico4, {
    type: 'bar',
    data: {
        labels: [],
        datasets: []
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});


function getColorForEstado(cantidad) {
    let color = ""

    if (cantidad > 15) {
        color = "green"
    } else if (cantidad >= 15 && cantidad <= 15) {
        color = 'yellow'
    } else if (cantidad >= 13 && cantidad < 15) {
        color = 'orange'
    } else if (cantidad == 13) {
        color = 'red'
    } else {
        color = 'gray'
    }

    return color;
}

function obtenerNombreMes(numeroMes) {
    const meses = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];
    return meses[numeroMes - 1];
}

const buscarDatos = async () => {
    const url = '/carbajal_final_aplicacion_armamentos_ingsoft1/graficas/datos';
    const config = {
        method: 'GET'
    };

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();

        const { codigo, mensaje, data, dataFechas, dataMeses, dataClientes } = datos;

        if (codigo === 1) {

            // GRÁFICO 1 - Productos más vendidos (Barras)
            const { armasPorTipo, armasPorEstado, actividadReciente } = data;

            if (window.funcionGrafico1 && armasPorTipo) {
                const etiquetasTipos = armasPorTipo.map(d => d.nombre_tipo);
                const cantidadesTipos = armasPorTipo.map(d => parseInt(d.total_cantidad));
                window.funcionGrafico1.data.labels = etiquetasTipos;

                window.funcionGrafico1.data.datasets = [{
                    label: 'Cantidad de Armas',
                    data: cantidadesTipos,
                    backgroundColor: cantidadesTipos.map(cantidad => getColorForEstado(cantidad)),
                    borderColor: cantidadesTipos.map(cantidad => getColorForEstado(cantidad)),
                    borderWidth: 1

                }];
                window.funcionGrafico1.update();
            }

            // GRÁFICO 2 - Distribución de productos (Pie)
            const coloresPie = [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384'
            ];

            if (window.funcionGrafico2 && armasPorEstado) {
                const etiquetasEstados = armasPorEstado.map(d => d.estado);
                const cantidadesEstados = armasPorEstado.map(d => parseInt(d.total_cantidad));

                window.funcionGrafico2.data = {
                    labels: etiquetasEstados,
                    datasets: [{
                        label: 'Distribución por Estado',
                        data: cantidadesEstados,
                        backgroundColor: coloresPie.slice(0, etiquetasEstados.length)
                    }]
                };
                window.funcionGrafico2.update();
            }

            // GRÁFICO 3 - Ventas por fecha (Línea)
            if (window.funcionGrafico3 && actividadReciente) {
                const fechas = actividadReciente.map(d => {
                    const fecha = new Date(d.fecha);
                    return fecha.toLocaleDateString('es-GT', {
                        month: 'short',
                        day: 'numeric'
                    });
                });

                const cantidadesAcciones = actividadReciente.map(d => parseInt(d.total_acciones));

                window.funcionGrafico3.data.labels = fechas;
                window.funcionGrafico3.data.datasets = [
                    {
                        label: 'del Sistema',
                        data: cantidadesAcciones,
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.4
                    }
                ];

                window.funcionGrafico3.update();
            }

            // GRÁFICO 4 - Ventas por mes (Barras)
            if (window.funcionGrafico4 && armasPorEstado) {
                const etiquetasEstados = armasPorEstado.map(d => d.estado);
                const cantidadesEstados = armasPorEstado.map(d => parseInt(d.total_cantidad));

                window.funcionGrafico4.data.labels = etiquetasEstados;
                window.funcionGrafico4.data.datasets = [
                    {
                        label: 'Cantidad por Estado',
                        data: cantidadesEstados,
                        backgroundColor: '#36A2EB',
                        borderColor: '#36A2EB',
                        borderWidth: 1
                    }
                ];

                window.funcionGrafico4.update();
            }

        } else {
            console.error('Error del servidor:', mensaje);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: mensaje
            });
        }

    } catch (error) {
        console.log('Error al buscar datos:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudieron cargar los datos'
        });
    }
}

buscarDatos();