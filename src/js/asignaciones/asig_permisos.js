import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { validarFormulario } from "../funciones";
import { lenguaje } from "../lenguaje";

console.log('=== CARGANDO MÓDULO DE ASIGNACIÓN DE PERMISOS ===');

// Variables principales
const FormAsigPermisos = document.getElementById('FormAsigPermisos');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const selectUsuario = document.getElementById('id_usuario');
const selectPermiso = document.getElementById('id_permiso_app');
const motivo = document.getElementById('motivo');

// Botones de navegación
const BtnVerAsignaciones = document.getElementById('BtnVerAsignaciones');
const BtnCrearAsignacion = document.getElementById('BtnCrearAsignacion');
const BtnActualizarTabla = document.getElementById('BtnActualizarTabla');
const seccionFormulario = document.getElementById('seccionFormulario');
const seccionTabla = document.getElementById('seccionTabla');
const tituloFormulario = document.getElementById('tituloFormulario');

// Mostrar Formulario
const mostrarFormulario = (titulo = 'Registrar Asignación de Permiso') => {
    seccionFormulario.classList.remove('d-none');
    seccionTabla.classList.add('d-none');
    tituloFormulario.textContent = titulo;
    seccionFormulario.scrollIntoView({ behavior: 'smooth', block: 'start' });
};

// Mostrar Tabla
const mostrarTabla = () => {
    seccionFormulario.classList.add('d-none');
    seccionTabla.classList.remove('d-none');
    buscaAsignaciones();
    seccionTabla.scrollIntoView({ behavior: 'smooth', block: 'start' });
};

// Cargar usuarios
const cargarUsuarios = async () => {
    try {
        const resp = await fetch('/carbajal_final_aplicacion_armamentos_ingsoft1/busca_usuario');
        const datos = await resp.json();
        selectUsuario.innerHTML = '<option value="">-- Selecciona un usuario --</option>';
        if (datos.codigo === 1 && datos.data) {
            datos.data.forEach(usuario => {
                const option = document.createElement('option');
                option.value = usuario.id_usuario;
                option.textContent = `${usuario.nombre1} ${usuario.apellido1} - DPI: ${usuario.dpi}`;
                selectUsuario.appendChild(option);
            });
        }
    } catch (e) {
        console.error('Error al cargar usuarios:', e);
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Error",
            text: "No se pudieron cargar los usuarios",
            showConfirmButton: false,
            timer: 2000
        });
    }
};

// Cargar permisos
const cargarPermisos = async () => {
    try {
        const resp = await fetch('/carbajal_final_aplicacion_armamentos_ingsoft1/busca_permiso_aplicacion');
        const datos = await resp.json();
        selectPermiso.innerHTML = '<option value="">-- Selecciona un permiso / aplicación --</option>';
        if (datos.codigo === 1 && datos.data) {
            datos.data.forEach(permiso => {
                const option = document.createElement('option');
                option.value = permiso.id_permiso_app;
                option.textContent = `${permiso.nombre_permiso} (${permiso.nombre_aplicacion}) - ${permiso.descripcion_permiso}`;
                selectPermiso.appendChild(option);
            });
        }
    } catch (e) {
        console.error('Error al cargar permisos:', e);
    }
};


// DataTable
const tablaAsignaciones = new DataTable('#TableAsignaciones', {
    dom: `<"row mt-3 justify-content-between"<"col" l><"col" B><"col-3" f>>t<"row mt-3 justify-content-between"<"col-md-3" i><"col-md-8" p>>`,
    language: lenguaje,
    data: [],
    columns: [
        { title: '#', data: 'id_asig_permiso', render: (data, type, row, meta) => meta.row + 1 },
        { title: 'Usuario', data: 'nombre_usuario', render: (data, type, row) => `${data} ${row.apellido_usuario}` },
        { title: 'DPI', data: 'dpi_usuario' },
        { title: 'Permiso', data: 'permiso' },
        { title: 'Aplicación', data: 'nombre_aplicacion' },
        { title: 'Motivo', data: 'motivo' },
        { title: 'Creación', data: 'fecha_creacion' },
        {
            title: 'Acciones', data: 'id_asig_permiso', render: (data, type, row) => `
                <button class="btn btn-warning btn-sm modificar mx-1" data-id="${data}" data-json='${JSON.stringify(row)}'>
                    <i class="bi bi-pencil-square"></i>
                </button>
                <button class="btn btn-danger btn-sm eliminar mx-1" data-id="${data}">
                    <i class="bi bi-trash3"></i>
                </button>`
        }
    ]
});

// Buscar asignaciones
const buscaAsignaciones = async () => {
    try {
        const resp = await fetch('/carbajal_final_aplicacion_armamentos_ingsoft1/busca_asig_permiso');
        const datos = await resp.json();
        tablaAsignaciones.clear().draw();
        if (datos.codigo === 1) tablaAsignaciones.rows.add(datos.data).draw();
    } catch (e) { console.error(e); }
};

// Guardar asignación
const guardaAsignacion = async (e) => {
    e.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(FormAsigPermisos, ['id_asig_permiso'])) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Formulario incompleto",
            text: "Complete todos los campos",
            showConfirmButton: false,
            timer: 1000
        });
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(FormAsigPermisos);
    const resp = await fetch('/carbajal_final_aplicacion_armamentos_ingsoft1/guarda_asig_permiso', { method: 'POST', body });
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
        limpiarFormulario();

        setTimeout(async () => {
            const resultado = await Swal.fire({
                title: '¿Desea ver las asignaciones registradas?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, ver asignaciones',
                cancelButtonText: 'Seguir asignando'
            });

            if (resultado.isConfirmed) {
                mostrarTabla();
            }
        }, 1000);

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
    BtnGuardar.disabled = false;
};

// Modificar asignación
const modificaAsignacion = async (e) => {
    e.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(FormAsigPermisos, [])) {
        Swal.fire("Error", "Complete todos los campos", "warning");
        BtnModificar.disabled = false;
        return;
    }

    const body = new FormData(FormAsigPermisos);
    const resp = await fetch('/carbajal_final_aplicacion_armamentos_ingsoft1/modifica_asig_permiso', { method: 'POST', body });
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
        limpiarFormulario();
        mostrarTabla();
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
    BtnModificar.disabled = false;
};

// Eliminar asignación
const eliminaAsignacion = async (e) => {
    const id = e.currentTarget.dataset.id;
    const confirm = await Swal.fire({
        title: "¿Eliminar?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    });

    if (!confirm.isConfirmed) return;

    const body = new FormData();
    body.append('id_asig_permiso', id);
    const resp = await fetch('/carbajal_final_aplicacion_armamentos_ingsoft1/elimina_asig_permiso', { method: 'POST', body });
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
        buscaAsignaciones();
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
};

// Llenar formulario para modificar
const llenarFormulario = async (e) => {
    const fila = JSON.parse(e.currentTarget.dataset.json);
    await cargarUsuarios();
    await cargarPermisos();

    document.getElementById('id_asig_permiso').value = fila.id_asig_permiso;
    selectUsuario.value = fila.id_usuario;
    selectPermiso.value = fila.id_permiso_app;
    motivo.value = fila.motivo;

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');
    mostrarFormulario("Modificar Asignación");
};

// Limpiar formulario
const limpiarFormulario = () => {
    FormAsigPermisos.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
    tituloFormulario.textContent = 'Registrar Asignación de Permiso';

    const inputs = FormAsigPermisos.querySelectorAll('.form-control, .form-select');
    inputs.forEach(input => input.classList.remove('is-valid', 'is-invalid'));
};

// Eventos
FormAsigPermisos.addEventListener('submit', guardaAsignacion);
BtnModificar.addEventListener('click', modificaAsignacion);
BtnLimpiar.addEventListener('click', limpiarFormulario);
BtnVerAsignaciones.addEventListener('click', mostrarTabla);
BtnCrearAsignacion.addEventListener('click', async () => {
    await cargarUsuarios();
    await cargarPermisos();
    limpiarFormulario();
    mostrarFormulario();
});
BtnActualizarTabla.addEventListener('click', buscaAsignaciones);
tablaAsignaciones.on('click', '.modificar', llenarFormulario);
tablaAsignaciones.on('click', '.eliminar', eliminaAsignacion);

// Inicialización
document.addEventListener('DOMContentLoaded', async () => {
    await cargarUsuarios();
    await cargarPermisos();
    limpiarFormulario();
    mostrarFormulario();
});
