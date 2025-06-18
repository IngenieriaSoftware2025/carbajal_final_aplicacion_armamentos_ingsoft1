import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { validarFormulario } from "../funciones";
import { lenguaje } from "../lenguaje";

// === VARIABLES GLOBALES ===
const Form = document.getElementById('FormPermisoAplicacion');

const BtnVerTabla = document.getElementById('BtnVerTabla');
const BtnCrearNuevaRelacion = document.getElementById('BtnCrearNuevaRelacion');
const BtnActualizarTabla = document.getElementById('BtnActualizarTabla');

const selectPermiso = document.getElementById('id_permiso');
const selectApp = document.getElementById('id_app');

const seccionFormulario = document.getElementById('seccionFormulario');
const seccionTabla = document.getElementById('seccionTabla');

// === DATATABLE ===
const tabla = new DataTable('#TablePermisoAplicacion', {
    dom: `<"row mt-3 justify-content-between"<"col" l><"col" B><"col-3" f>>t<"row mt-3 justify-content-between"<"col-md-3" i><"col-md-8" p>>`,
    language: lenguaje,
    data: [],
    columns: [
        { title: '#', data: 'id_permiso_app', render: (d, t, r, m) => m.row + 1 },
        { title: 'Permiso', data: 'nombre_permiso' },
        { title: 'Clave', data: 'clave_permiso' },
        { title: 'Descripción', data: 'descripcion_permiso' },
        { title: 'Aplicación', data: 'nombre_aplicacion' },
        {
            title: 'Acciones',
            data: 'id_permiso_app',
            render: id => `<button class="btn btn-danger btn-sm eliminar" data-id="${id}"><i class="bi bi-trash3"></i></button>`
        }
    ]
});

// === FUNCIONES PARA CAMBIO DE VISTA ===
const mostrarFormulario = () => {
    seccionFormulario.classList.remove('d-none');
    seccionTabla.classList.add('d-none');
    Form.reset();
};

const mostrarTabla = () => {
    seccionFormulario.classList.add('d-none');
    seccionTabla.classList.remove('d-none');
    buscar();
};

// === CARGA DE PERMISOS ===
const cargarPermisos = async () => {
    try {
        const url = '/carbajal_final_aplicacion_armamentos_ingsoft1/busca_permiso';
        const config = { method: 'GET' };
        const resp = await fetch(url, config);
        const data = await resp.json();
        selectPermiso.innerHTML = '<option value="">Seleccione un permiso</option>';
        data.data.forEach(p => {
            const opt = document.createElement('option');
            opt.value = p.id_permiso;
            opt.textContent = `${p.nombre_permiso} (${p.descripcion})`;
            selectPermiso.appendChild(opt);
        });
    } catch (err) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo cargar la lista de permisos",
            showConfirmButton: false,
            timer: 1000
        });
    }
};

// === CARGA DE APLICACIONES ===
const cargarAplicaciones = async () => {
    try {
        const url = '/carbajal_final_aplicacion_armamentos_ingsoft1/busca_aplicacion';
        const config = { method: 'GET' };
        const resp = await fetch(url, config);
        const data = await resp.json();
        selectApp.innerHTML = '<option value="">Seleccione una aplicación</option>';
        data.data.forEach(app => {
            const opt = document.createElement('option');
            opt.value = app.id_app;
            opt.textContent = app.nombre_app_md;
            selectApp.appendChild(opt);
        });
    } catch (err) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo cargar aplicaciones",
            showConfirmButton: false,
            timer: 1000
        });
    }
};

// === BUSCAR DATOS ===
const buscar = async () => {
    try {
        const url = '/carbajal_final_aplicacion_armamentos_ingsoft1/busca_permiso_aplicacion';
        const config = { method: 'GET' };
        const resp = await fetch(url, config);
        const data = await resp.json();
        tabla.clear().rows.add(data.data).draw();
    } catch (err) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo obtener los datos",
            showConfirmButton: false,
            timer: 1000
        });
    }
};

// === GUARDAR DATOS ===
const guardar = async (e) => {
    e.preventDefault();
    // Ahora sí valida ambos select: id_permiso y id_app
    if (!validarFormulario(Form, ['id_permiso_app'])) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Formulario incompleto",
            text: "Por favor, completa todos los campos",
            showConfirmButton: false,
            timer: 1000
        });
        return;
    }

    try {
        const body = new FormData(Form);
        const resp = await fetch('/carbajal_final_aplicacion_armamentos_ingsoft1/guarda_permiso_aplicacion', {
            method: 'POST',
            body
        });
        const datos = await resp.json();

        if (datos.codigo === 1) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Éxito!",
                text: datos.mensaje,
                showConfirmButton: false,
                timer: 1000
            });
            buscar();
            Form.reset();
        } else {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: datos.mensaje,
                showConfirmButton: false,
                timer: 1000
            });
        }
    } catch (err) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo conectar con el servidor",
            showConfirmButton: false,
            timer: 1000
        });
    }
};

// === ELIMINAR REGISTRO ===
const eliminar = async (e) => {
    const id = e.target.dataset.id;
    const confirm = await Swal.fire({
        position: "center",
        icon: "warning",
        title: "¿Eliminar?",
        showCancelButton: true,
        confirmButtonColor: "#d33"
    });
    if (!confirm.isConfirmed) return;

    try {
        const body = new FormData();
        body.append('id_permiso_app', id);
        const resp = await fetch('/carbajal_final_aplicacion_armamentos_ingsoft1/elimina_permiso_aplicacion', {
            method: 'POST',
            body
        });
        const datos = await resp.json();

        if (datos.codigo === 1) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Éxito!",
                text: "Registro eliminado",
                showConfirmButton: false,
                timer: 1000
            });
            buscar();
        } else {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: datos.mensaje,
                showConfirmButton: false,
                timer: 1000
            });
        }
    } catch (err) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo conectar con el servidor",
            showConfirmButton: false,
            timer: 1000
        });
    }
};

// === EVENTOS ===
tabla.on('click', '.eliminar', eliminar);
Form.addEventListener('submit', guardar);
BtnVerTabla.addEventListener('click', mostrarTabla);
BtnCrearNuevaRelacion.addEventListener('click', mostrarFormulario);
BtnActualizarTabla.addEventListener('click', () => {
    buscar();
    Swal.fire({
        position: "center",
        icon: "success",
        title: "¡Actualizado!",
        showConfirmButton: false,
        timer: 1000
    });
});

// === INICIALIZACIÓN ===
document.addEventListener('DOMContentLoaded', async () => {
    await cargarPermisos();
    await cargarAplicaciones();
    mostrarFormulario();
});
