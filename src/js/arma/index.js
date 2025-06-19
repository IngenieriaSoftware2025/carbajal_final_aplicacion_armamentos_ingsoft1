import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { validarFormulario } from "../funciones";
import { lenguaje } from "../lenguaje";

// Elementos del DOM
const FormArma = document.getElementById("FormArma");
const BtnGuardar = document.getElementById("BtnGuardar");
const BtnModificar = document.getElementById("BtnModificar");
const BtnLimpiar = document.getElementById("BtnLimpiar");
const BtnVerLista = document.getElementById("BtnVerLista");
const BtnNuevo = document.getElementById("BtnNuevo");
const seccionTabla = document.getElementById("seccionTabla");
const selectTipoArma = document.getElementById("id_tipo_arma");
const selectUsuario = document.getElementById("id_usuario");

// --- VISTAS ---
const mostrarFormulario = () => {
    FormArma.parentElement.parentElement.classList.remove('d-none');
    seccionTabla.classList.add('d-none');
    limpiarFormulario();
};

const mostrarTabla = () => {
    FormArma.parentElement.parentElement.classList.add('d-none');
    seccionTabla.classList.remove('d-none');
    obtenerArmas();
};

const limpiarFormulario = () => {
    FormArma.reset();
    BtnGuardar.classList.remove("d-none");
    BtnModificar.classList.add("d-none");
};

// --- CARGA DE DATOS PARA SELECTS ---
const cargarTiposArmas = async () => {
    const url = "/carbajal_final_aplicacion_armamentos_ingsoft1/busca_tipo_arma";
    try {
        const respuesta = await fetch(url);
        const datos = await respuesta.json();
        selectTipoArma.innerHTML = '<option value="">Seleccione un tipo...</option>';
        if (datos.codigo === 1 && datos.data) {
            datos.data.forEach(tipo => {
                selectTipoArma.innerHTML += `<option value="${tipo.id_tipo_arma}">${tipo.nombre_tipo} (${tipo.calibre})</option>`;
            });
        }
    } catch (error) {
        console.error("Error al cargar tipos de armas:", error);
    }
};

const cargarUsuarios = async () => {
    const url = "/carbajal_final_aplicacion_armamentos_ingsoft1/busca_usuario";
    try {
        const respuesta = await fetch(url);
        const datos = await respuesta.json();
        selectUsuario.innerHTML = '<option value="">Seleccione un usuario...</option>';
        if (datos.codigo === 1 && datos.data) {
            datos.data.forEach(usuario => {
                selectUsuario.innerHTML += `<option value="${usuario.id_usuario}">${usuario.nombre1} ${usuario.apellido1}</option>`;
            });
        }
    } catch (error) {
        console.error("Error al cargar usuarios:", error);
    }
};

// --- OPERACIONES CRUD ---
const guardarArma = async (e) => {
    e.preventDefault();
    if (!validarFormulario(FormArma, ['id_arma'])) {
        Swal.fire("Campos incompletos", "Debe llenar todos los campos requeridos", "warning");
        return;
    }

    BtnGuardar.disabled = true;
    const body = new FormData(FormArma);
    const url = '/carbajal_final_aplicacion_armamentos_ingsoft1/guarda_arma';
    const config = { method: 'POST', body };

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();

        if (datos.codigo === 1) {
            Swal.fire("¡Éxito!", datos.mensaje, "success");
            limpiarFormulario();
        } else {
            Swal.fire("Error", datos.mensaje, "error");
        }
    } catch (error) {
        console.log(error);
        Swal.fire("Error de Conexión", "No se pudo conectar con el servidor", "error");
    }
    BtnGuardar.disabled = false;
};

const modificarArma = async (e) => {
    e.preventDefault();
    if (!validarFormulario(FormArma)) {
        Swal.fire("Campos incompletos", "Debe llenar todos los campos requeridos", "warning");
        return;
    }

    BtnModificar.disabled = true;
    const body = new FormData(FormArma);
    const url = '/carbajal_final_aplicacion_armamentos_ingsoft1/modifica_arma';
    const config = { method: 'POST', body };

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();

        if (datos.codigo === 1) {
            Swal.fire("¡Modificado!", datos.mensaje, "success");
            limpiarFormulario();
            mostrarTabla();
        } else {
            Swal.fire("Error", datos.mensaje, "error");
        }
    } catch (error) {
        console.log(error);
        Swal.fire("Error de Conexión", "No se pudo conectar con el servidor", "error");
    }
    BtnModificar.disabled = false;
};

const eliminarArma = async (id) => {
    const confirmacion = await Swal.fire({
        title: "¿Está seguro de eliminar el registro?",
        text: "Esta acción es irreversible.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    });

    if (confirmacion.isConfirmed) {
        const body = new FormData();
        body.append('id_arma', id);
        const url = '/carbajal_final_aplicacion_armamentos_ingsoft1/elimina_arma';
        const config = { method: 'POST', body };

        try {
            const respuesta = await fetch(url, config);
            const datos = await respuesta.json();

            if (datos.codigo === 1) {
                Swal.fire("Eliminado", datos.mensaje, "success");
                obtenerArmas();
            } else {
                Swal.fire("Error", datos.mensaje, "error");
            }
        } catch (error) {
            console.log(error);
            Swal.fire("Error de Conexión", "No se pudo conectar con el servidor", "error");
        }
    }
};

// --- DATATABLE ---
const datosTabla = new DataTable("#TableArmas", {
    language: lenguaje,
    columns: [
        { title: "ID", data: "id_arma" },
        { title: "Tipo de Arma", data: "nombre_tipo" },
        { title: "Propietario", data: "nombre_usuario" },
        { title: "Cantidad", data: "cantidad" },
        { title: "Estado", data: "estado" },
        {
            title: "Acciones",
            data: "id_arma",
            render: (data) => `
                <button class="btn btn-warning btn-sm editar" data-id="${data}"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-danger btn-sm eliminar" data-id="${data}"><i class="bi bi-trash"></i></button>
            `
        }
    ]
});

const obtenerArmas = async () => {
    // Se asume que /busca_arma devuelve los datos con JOIN para nombre_tipo y nombre_usuario
    const url = '/carbajal_final_aplicacion_armamentos_ingsoft1/busca_arma';
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
        console.error('Error al obtener armas:', error);
    }
};

const editarArma = async (id) => {
    const url = `/carbajal_final_aplicacion_armamentos_ingsoft1/busca_arma?id_arma=${id}`;
    try {
        const respuesta = await fetch(url);
        const datos = await respuesta.json();

        if (datos.codigo === 1 && datos.data.length > 0) {
            const arma = datos.data[0];
            document.getElementById("id_arma").value = arma.id_arma;
            selectTipoArma.value = arma.id_tipo_arma;
            selectUsuario.value = arma.id_usuario;
            document.getElementById("cantidad").value = arma.cantidad;
            document.getElementById("estado").value = arma.estado;

            BtnGuardar.classList.add("d-none");
            BtnModificar.classList.remove("d-none");
            mostrarFormulario();
        }
    } catch (error) {
        console.error("Error al buscar arma para editar:", error);
    }
};

// --- EVENTOS ---
FormArma.addEventListener("submit", guardarArma);
BtnModificar.addEventListener("click", modificarArma);
BtnLimpiar.addEventListener("click", limpiarFormulario);
BtnVerLista.addEventListener("click", mostrarTabla);
BtnNuevo.addEventListener("click", mostrarFormulario);

datosTabla.on("click", ".editar", (e) => editarArma(e.currentTarget.dataset.id));
datosTabla.on("click", ".eliminar", (e) => eliminarArma(e.currentTarget.dataset.id));

// --- INICIALIZACIÓN ---
document.addEventListener("DOMContentLoaded", () => {
    cargarTiposArmas();
    cargarUsuarios();
    mostrarFormulario();
});
