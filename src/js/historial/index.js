import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { validarFormulario } from "../funciones";
import { lenguaje } from "../lenguaje";

// === Referencias ===
const tablaHistorial = new DataTable('#TableHistorial', {
    language: lenguaje,
    columns: [
        { title: "ID", data: "id_hist_act" },
        { title: "Usuario", data: "nombre_usuario" },
        { title: "Ruta", data: "descripcion_ruta" },
        { title: "Ejecución", data: "ejecucion" },
        { title: "Status", data: "status" },
        { title: "Fecha", data: "fecha_creacion" }
    ]
});

const obtenerHistorial = async () => {
    const url = "/carbajal_final_aplicacion_armamentos_ingsoft1/busca_historial";
    const respuesta = await fetch(url);
    const datos = await respuesta.json();

    tablaHistorial.clear().draw();
    if (datos.codigo === 1) {
        tablaHistorial.rows.add(datos.data).draw();
    }
};

// === Inicialización ===
document.addEventListener('DOMContentLoaded', () => {
    obtenerHistorial();
});
