import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { validarFormulario } from "../funciones";
import { lenguaje } from "../lenguaje";

const FormTipoArma = document.getElementById("FormTipoArma");
const BtnGuardar = document.getElementById("BtnGuardar");
const BtnModificar = document.getElementById("BtnModificar");
const BtnLimpiar = document.getElementById("BtnLimpiar");
const BtnVerLista = document.getElementById("BtnVerLista");
const BtnNuevo = document.getElementById("BtnNuevo");
const seccionTabla = document.getElementById("seccionTabla");

// Vista
const mostrarFormulario = () => {
    FormTipoArma.parentElement.parentElement.classList.remove('d-none');
    seccionTabla.classList.add('d-none');
    limpiarFormulario();
};

const mostrarTabla = () => {
    FormTipoArma.parentElement.parentElement.classList.add('d-none');
    seccionTabla.classList.remove('d-none');
    obtenerTiposArmas();
};

const limpiarFormulario = () => {
    FormTipoArma.reset();
    BtnGuardar.classList.remove("d-none");
    BtnModificar.classList.add("d-none");
};

const guardarTipoArma = async (e) => {
    e.preventDefault();

    if (!validarFormulario(FormTipoArma, ['id_tipo_arma'])) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Formulario incompleto",
            text: "Complete los campos obligatorios",
            showConfirmButton: false,
            timer: 1000
        });
        return;
    }

    BtnGuardar.disabled = true;
    const body = new FormData(FormTipoArma);
    const url = '/carbajal_final_aplicacion_armamentos_ingsoft1/guarda_tipo_arma';
    const config = {
        method: 'POST',
        body
    };

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();

        if (datos.codigo === 1) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Éxito!",
                text: datos.mensaje,
                showConfirmButton: false,
                timer: 1000
            });
            limpiarFormulario();
        } else {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: datos.mensaje,
                showConfirmButton: false,
                timer: 2000
            });
        }
    } catch (error) {
        console.log(error);
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo conectar con el servidor",
            showConfirmButton: false,
            timer: 1000
        });
    }
    BtnGuardar.disabled = false;
};

// Configuración de la DataTable
const datosTabla = new DataTable("#TableTiposArmas", {
    language: lenguaje,
    columns: [
        { title: "ID", data: "id_tipo_arma" },
        { title: "Tipo de Arma", data: "nombre_tipo" },
        { title: "Calibre", data: "calibre" },
        {
            title: "Acciones",
            data: "id_tipo_arma",
            render: (data) => `
                <button class="btn btn-warning btn-sm editar" data-id="${data}"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-danger btn-sm eliminar" data-id="${data}"><i class="bi bi-trash"></i></button>
            `
        }
    ]
});

const obtenerTiposArmas = async () => {
    const url = '/carbajal_final_aplicacion_armamentos_ingsoft1/busca_tipo_arma';
    try {
        const respuesta = await fetch(url);
        const datos = await respuesta.json();

        if (datos.codigo === 1) {
            datosTabla.clear().draw();
            if (datos.data && datos.data.length > 0) {
                datosTabla.rows.add(datos.data).draw();
            }
        } else {
            console.log('Error del servidor:', datos.mensaje);
        }
    } catch (error) {
        console.error('Error completo:', error);
    }
};

const editarTipoArma = async (id) => {
    const url = `/carbajal_final_aplicacion_armamentos_ingsoft1/busca_tipo_arma?id_tipo_arma=${id}`;
    const respuesta = await fetch(url);
    const datos = await respuesta.json();

    if (datos.codigo === 1 && datos.data.length > 0) {
        const tipoArma = datos.data[0];
        document.getElementById("id_tipo_arma").value = tipoArma.id_tipo_arma;
        document.getElementById("nombre_tipo").value = tipoArma.nombre_tipo;
        document.getElementById("calibre").value = tipoArma.calibre;

        BtnGuardar.classList.add("d-none");
        BtnModificar.classList.remove("d-none");
        mostrarFormulario();
    }
};

const modificarTipoArma = async (e) => {
    e.preventDefault();

    if (!validarFormulario(FormTipoArma)) return;

    BtnModificar.disabled = true;
    const datos = new FormData(FormTipoArma);
    const url = "/carbajal_final_aplicacion_armamentos_ingsoft1/modifica_tipo_arma";

    const respuesta = await fetch(url, { method: "POST", body: datos });
    const resultado = await respuesta.json();

    if (resultado.codigo === 1) {
        Swal.fire("Modificado", resultado.mensaje, "success");
        limpiarFormulario();
        mostrarTabla();
    } else {
        Swal.fire("Error", resultado.mensaje, "error");
    }
    BtnModificar.disabled = false;
};

const eliminarTipoArma = async (id) => {
    const confirmacion = await Swal.fire({
        title: "¿Está seguro de eliminar este tipo de arma?",
        text: "Esta acción no se puede deshacer.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    });

    if (confirmacion.isConfirmed) {
        const datos = new FormData();
        datos.append("id_tipo_arma", id);

        const url = "/carbajal_final_aplicacion_armamentos_ingsoft1/elimina_tipo_arma";
        const respuesta = await fetch(url, { method: "POST", body: datos });
        const resultado = await respuesta.json();

        if (resultado.codigo === 1) {
            Swal.fire("Eliminado", resultado.mensaje, "success");
            obtenerTiposArmas();
        } else {
            Swal.fire("Error", resultado.mensaje, "error");
        }
    }
};

// Eventos
FormTipoArma.addEventListener("submit", guardarTipoArma);
BtnModificar.addEventListener("click", modificarTipoArma);
BtnLimpiar.addEventListener("click", limpiarFormulario);
BtnVerLista.addEventListener("click", mostrarTabla);
BtnNuevo.addEventListener("click", mostrarFormulario);

datosTabla.on("click", ".editar", (e) => editarTipoArma(e.currentTarget.dataset.id));
datosTabla.on("click", ".eliminar", (e) => eliminarTipoArma(e.currentTarget.dataset.id));

// Inicialización
document.addEventListener("DOMContentLoaded", () => {
    mostrarFormulario();
});