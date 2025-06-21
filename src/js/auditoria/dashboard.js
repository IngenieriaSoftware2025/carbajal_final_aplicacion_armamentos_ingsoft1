import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { validarFormulario } from "../funciones";
import { lenguaje } from "../lenguaje";

// === Referencias ===
const tablaAuditoria = new DataTable('#TableAuditoria', {
    language: lenguaje,
    columns: [
        { title: "ID", data: "id" },
        { title: "Usuario", data: "usuario" },
        { title: "Ruta", data: "ruta" },
        { title: "Ejecución", data: "ejecucion" },
        { title: "Status", data: "status" },
        { title: "Fecha", data: "fecha" }
    ],
    order: [[5, 'desc']], // Ordenar por fecha descendente
    pageLength: 25
});

// === Función para obtener datos de auditoría ===
const obtenerAuditoria = async () => {
    try {
        const url = "/carbajal_final_aplicacion_armamentos_ingsoft1/api/auditoria_dashboard";
        const respuesta = await fetch(url);
        const datos = await respuesta.json();

        // Limpiar tabla
        tablaAuditoria.clear().draw();

        if (datos.codigo === 1) {
            // Llenar tabla
            tablaAuditoria.rows.add(datos.data.historial).draw();

            // Actualizar estadísticas
            actualizarEstadisticas(datos.data.estadisticas);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: datos.mensaje
            });
        }
    } catch (error) {
        console.error('Error al obtener auditoría:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión con el servidor'
        });
    }
};

// === Función para actualizar estadísticas ===
const actualizarEstadisticas = (stats) => {
    document.getElementById('totalRegistros').textContent = stats.total_registros || 0;
    document.getElementById('usuariosUnicos').textContent = stats.usuarios_unicos || 0;
    document.getElementById('ultimaActividad').textContent = 'Hace 5 min'; // Esto lo calculas después
    document.getElementById('estadoGeneral').textContent = 'Activo';
};

// === Auto-refresh cada 30 segundos ===
const iniciarAutoRefresh = () => {
    setInterval(() => {
        obtenerAuditoria();
    }, 30000); // 30 segundos
};

// === Inicialización ===
document.addEventListener('DOMContentLoaded', () => {
    obtenerAuditoria();
    iniciarAutoRefresh();

    // Botón manual de refresh (opcional)
    const btnRefresh = document.getElementById('btnRefresh');
    if (btnRefresh) {
        btnRefresh.addEventListener('click', obtenerAuditoria);
    }
});