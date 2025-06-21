import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { validarFormulario } from "../funciones";
import { lenguaje } from "../lenguaje";

console.log("Cargando rutas.js");

const BtnVolver = document.getElementById("BtnVolver");
const seccionTabla = document.getElementById("seccionTabla");

document.addEventListener("DOMContentLoaded", () => {
    new DataTable("#TableRutas", {
        language: lenguaje,
        ajax: {
            url: "/carbajal_final_aplicacion_armamentos_ingsoft1/busca_ruta",
            dataSrc: "data"
        },
        columns: [
            { title: "ID", data: "id_ruta" },
            { title: "Aplicación", data: "nombre_app_md" },
            { title: "Ruta", data: "ruta" },
            { title: "Descripción", data: "descripcion" }
        ],
        responsive: true
    });

    seccionTabla.classList.remove("d-none");

    // Evento Volver
    BtnVolver.addEventListener("click", () => {
        window.location.href = "/carbajal_final_aplicacion_armamentos_ingsoft1/";
    });
});
