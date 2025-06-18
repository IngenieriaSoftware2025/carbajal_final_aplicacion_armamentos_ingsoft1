import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { validarFormulario } from "../funciones";
import { lenguaje } from "../lenguaje";

console.log('=== CARGANDO MÓDULO DE PERMISOS ===');

// CONSTANTES DEL FORMULARIO ESPECÍFICAS DE PERMISOS
const FormPermisos = document.getElementById('FormPermisos');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');

// CAMPOS ESPECÍFICOS DE PERMISOS
const nombrePermiso = document.getElementById('nombre_permiso');
const clavePermiso = document.getElementById('clave_permiso');
const descripcionPermiso = document.getElementById('descripcion');

// BOTONES DE ACCIÓN
const BtnVerPermisos = document.getElementById('BtnVerPermisos');
const BtnCrearPermiso = document.getElementById('BtnCrearPermiso');
const BtnActualizarTabla = document.getElementById('BtnActualizarTabla');

// SECCIONES DEL FORMULARIO Y TABLA
const seccionFormulario = document.getElementById('seccionFormulario');
const seccionTabla = document.getElementById('seccionTabla');
const tituloFormulario = document.getElementById('tituloFormulario');

// FUNCIONES PARA CAMBIAR VISTAS
const mostrarFormulario = (titulo = 'Registrar Permiso') => {
    seccionFormulario.classList.remove('d-none');
    seccionTabla.classList.add('d-none');
    tituloFormulario.textContent = titulo;

    // Scroll al formulario
    seccionFormulario.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

const mostrarTabla = () => {
    seccionFormulario.classList.add('d-none');
    seccionTabla.classList.remove('d-none');

    // Actualizar datos automáticamente
    buscaPermiso();

    // Scroll a la tabla
    seccionTabla.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

// VALIDACIONES ESPECÍFICAS DE PERMISOS

// Validación de nombre del permiso
const validacionNombrePermiso = () => {
    const texto = nombrePermiso.value.trim();

    if (texto.length < 1) {
        nombrePermiso.classList.remove('is-valid', 'is-invalid');
        return;
    }

    if (texto.length < 5) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Nombre muy corto",
            text: "El nombre del permiso debe tener al menos 5 caracteres",
            showConfirmButton: false,
            timer: 1500
        });
        nombrePermiso.classList.remove('is-valid');
        nombrePermiso.classList.add('is-invalid');
    } else if (texto.length > 255) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Nombre muy largo",
            text: "El nombre del permiso no puede exceder 255 caracteres",
            showConfirmButton: false,
            timer: 1500
        });
        nombrePermiso.classList.remove('is-valid');
        nombrePermiso.classList.add('is-invalid');
    } else {
        nombrePermiso.classList.remove('is-invalid');
        nombrePermiso.classList.add('is-valid');
    }
}

// Validación de clave del permiso
const validacionClavePermiso = () => {
    const texto = clavePermiso.value.trim().toUpperCase();
    clavePermiso.value = texto; // Forzar mayúsculas

    if (texto.length < 1) {
        clavePermiso.classList.remove('is-valid', 'is-invalid');
        return;
    }

    if (texto.length < 2) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Clave muy corta",
            text: "La clave del permiso debe tener al menos 2 caracteres",
            showConfirmButton: false,
            timer: 1500
        });
        clavePermiso.classList.remove('is-valid');
        clavePermiso.classList.add('is-invalid');
    } else if (texto.length > 100) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Clave muy larga",
            text: "La clave del permiso no puede exceder 100 caracteres",
            showConfirmButton: false,
            timer: 1500
        });
        clavePermiso.classList.remove('is-valid');
        clavePermiso.classList.add('is-invalid');
    } else {
        clavePermiso.classList.remove('is-invalid');
        clavePermiso.classList.add('is-valid');
    }
}

// Validación de descripción
const validacionDescripcion = () => {
    const texto = descripcionPermiso.value.trim();

    if (texto.length < 1) {
        descripcionPermiso.classList.remove('is-valid', 'is-invalid');
        return;
    }

    if (texto.length < 10) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Descripción muy corta",
            text: "La descripción debe tener al menos 10 caracteres",
            showConfirmButton: false,
            timer: 1500
        });
        descripcionPermiso.classList.remove('is-valid');
        descripcionPermiso.classList.add('is-invalid');
    } else if (texto.length > 1000) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Descripción muy larga",
            text: "La descripción no puede exceder 1000 caracteres",
            showConfirmButton: false,
            timer: 1500
        });
        descripcionPermiso.classList.remove('is-valid');
        descripcionPermiso.classList.add('is-invalid');
    } else {
        descripcionPermiso.classList.remove('is-invalid');
        descripcionPermiso.classList.add('is-valid');
    }
}

// DATATABLE PARA PERMISOS
const datosDeTabla = new DataTable('#TablePermisos', {
    dom: `
        <"row mt-3 justify-content-between"
            <"col" l>
            <"col" B>
            <"col-3" f>
        >
        t
        <"row mt-3 justify-content-between"
            <"col-md-3 d-flex align-items-center" i>
            <"col-md-8 d-flex justify-content-end" p>
        >
    `,
    language: lenguaje,
    data: [],
    columns: [
        {
            title: 'N°',
            data: 'id_permiso',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        {
            title: 'Nombre del Permiso',
            data: 'nombre_permiso',
            width: '25%'
        },
        {
            title: 'Clave',
            data: 'clave_permiso',
            width: '15%',
            render: (data, type, row) => {
                return `<code class="bg-secondary text-white p-1 rounded">${data}</code>`;
            }
        },
        {
            title: 'Descripción',
            data: 'descripcion',
            width: '30%',
            render: (data, type, row) => {
                return data.length > 50 ? data.substring(0, 50) + '...' : data;
            }
        },
        {
            title: 'Opciones',
            data: 'id_permiso',
            searchable: false,
            orderable: false,
            width: '10%',
            render: (data, type, row, meta) => {
                return `
                <div class='d-flex justify-content-center'>
                    <button class='btn btn-warning btn-sm modificar mx-1' 
                        data-id="${data}" 
                        data-nombre_permiso="${row.nombre_permiso}"  
                        data-clave_permiso="${row.clave_permiso}"
                        data-descripcion="${row.descripcion}"
                        title="Modificar permiso">
                        <i class='bi bi-pencil-square'></i>
                    </button>
                    <button class='btn btn-danger btn-sm eliminar mx-1' 
                        data-id="${data}"
                        title="Eliminar permiso">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>
                `;
            }
        },
    ],
});

// GUARDAR PERMISO
const guardaPermiso = async (e) => {
    e.preventDefault();
    BtnGuardar.disabled = true;

    // Validar formulario (excluyendo id_permiso)
    if (!validarFormulario(FormPermisos, ['id_permiso'])) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Formulario incompleto",
            text: "Complete todos los campos obligatorios",
            showConfirmButton: false,
            timer: 1500
        });
        BtnGuardar.disabled = false;
        return;
    }

    // Validaciones específicas
    validacionNombrePermiso();
    validacionClavePermiso();
    validacionDescripcion();

    // Verificar si hay errores de validación
    const errores = FormPermisos.querySelectorAll('.is-invalid');
    if (errores.length > 0) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Datos incorrectos",
            text: "Corrija los errores en el formulario",
            showConfirmButton: false,
            timer: 2000
        });
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(FormPermisos);
    console.log('=== DATOS DE PERMISO QUE SE ENVÍAN ===');
    for (let [key, value] of body.entries()) {
        console.log(`${key}: ${value}`);
    }

    const url = '/carbajal_final_aplicacion_armamentos_ingsoft1/guarda_permiso';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        console.log('=== RESPUESTA DEL SERVIDOR ===');
        console.log('Status:', respuesta.status);
        console.log('StatusText:', respuesta.statusText);

        const datos = await respuesta.json();
        console.log('Datos recibidos:', datos);

        if (datos.codigo === 1) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Permiso registrado!",
                text: datos.mensaje,
                showConfirmButton: false,
                timer: 1500
            });

            limpiarFormulario();

            setTimeout(async () => {
                const resultado = await Swal.fire({
                    title: '¿Desea ver los permisos registrados?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, ver permisos',
                    cancelButtonText: 'Seguir registrando'
                });

                if (resultado.isConfirmed) {
                    mostrarTabla();
                }
            }, 1500);

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
        console.log('=== ERROR COMPLETO ===');
        console.log(error);
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo conectar con el servidor",
            showConfirmButton: false,
            timer: 2000
        });
    }
    BtnGuardar.disabled = false;
}

// BUSCAR PERMISOS
const buscaPermiso = async () => {
    const url = '/carbajal_final_aplicacion_armamentos_ingsoft1/busca_permiso';
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);

        // Verificar si la respuesta es OK
        if (!respuesta.ok) {
            console.error('Error HTTP:', respuesta.status, respuesta.statusText);
            return;
        }

        // Verificar si es JSON válido
        const textoRespuesta = await respuesta.text();
        console.log('Respuesta cruda del servidor:', textoRespuesta);

        let datos;
        try {
            datos = JSON.parse(textoRespuesta);
        } catch (errorJSON) {
            console.error('Error parseando JSON:', errorJSON);
            console.error('Respuesta del servidor:', textoRespuesta);
            return;
        }

        console.log('Datos parseados:', datos);

        if (datos.codigo === 1) {
            datosDeTabla.clear().draw();
            if (datos.data && datos.data.length > 0) {
                datosDeTabla.rows.add(datos.data).draw();
            }
        } else {
            console.log('Error del servidor:', datos.mensaje);
        }

    } catch (error) {
        console.error('Error completo:', error);
    }
}

// LLENAR FORMULARIO PARA MODIFICAR
const llenarFormulario = async (e) => {
    const datos = e.currentTarget.dataset;


    document.getElementById('id_permiso').value = datos.id;
    document.getElementById('nombre_permiso').value = datos.nombre_permiso;
    document.getElementById('clave_permiso').value = datos.clave_permiso;
    document.getElementById('descripcion').value = datos.descripcion;

    // Limpiar validaciones previas
    const inputs = FormPermisos.querySelectorAll('.form-control, .form-select');
    inputs.forEach(input => {
        input.classList.remove('is-valid', 'is-invalid');
    });

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    mostrarFormulario('Modificar Permiso');
}

// LIMPIAR FORMULARIO
const limpiarFormulario = () => {
    FormPermisos.reset();

    // Limpiar validaciones
    const inputs = FormPermisos.querySelectorAll('.form-control, .form-select');
    inputs.forEach(input => {
        input.classList.remove('is-valid', 'is-invalid');
    });

    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');

    // Cambiar título cuando se limpie
    tituloFormulario.textContent = 'Registrar Permiso';
}

// MODIFICAR PERMISO
const modificaPermiso = async (e) => {
    e.preventDefault();
    BtnModificar.disabled = true;

    // Validar formulario
    if (!validarFormulario(FormPermisos, [])) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Formulario incompleto",
            text: "Complete todos los campos obligatorios",
            showConfirmButton: false,
            timer: 1500
        });
        BtnModificar.disabled = false;
        return;
    }

    // Validaciones específicas
    validacionNombrePermiso();
    validacionClavePermiso();
    validacionDescripcion();

    // Verificar si hay errores de validación
    const errores = FormPermisos.querySelectorAll('.is-invalid');
    if (errores.length > 0) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Datos incorrectos",
            text: "Corrija los errores en el formulario",
            showConfirmButton: false,
            timer: 2000
        });
        BtnModificar.disabled = false;
        return;
    }

    const body = new FormData(FormPermisos);
    const url = '/carbajal_final_aplicacion_armamentos_ingsoft1/modifica_permiso';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();

        if (datos.codigo === 1) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Permiso actualizado!",
                text: datos.mensaje,
                showConfirmButton: false,
                timer: 1500
            });

            limpiarFormulario();
            setTimeout(() => {
                mostrarTabla();
            }, 1500);

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
            timer: 2000
        });
    }
    BtnModificar.disabled = false;
}

// ELIMINAR PERMISO
const eliminaPermiso = async (e) => {
    const idPermiso = e.currentTarget.dataset.id;

    const alertaConfirmaEliminar = await Swal.fire({
        position: "center",
        icon: "question",
        title: "¿Estás seguro?",
        text: "El permiso será eliminado del sistema",
        showConfirmButton: true,
        confirmButtonText: "Sí, eliminar",
        confirmButtonColor: "#d33",
        cancelButtonText: "Cancelar",
        showCancelButton: true
    });

    if (!alertaConfirmaEliminar.isConfirmed) return;

    const body = new FormData();
    body.append('id_permiso', idPermiso);

    try {
        const respuesta = await fetch('/carbajal_final_aplicacion_armamentos_ingsoft1/elimina_permiso', {
            method: 'POST',
            body
        });

        const datos = await respuesta.json();

        if (datos.codigo === 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Eliminado!",
                text: datos.mensaje,
                showConfirmButton: false,
                timer: 1500
            });
            buscaPermiso();
        } else {
            await Swal.fire({
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
            timer: 2000
        });
    }
};

// EVENTOS DE VALIDACIÓN
nombrePermiso.addEventListener('blur', validacionNombrePermiso);
clavePermiso.addEventListener('blur', validacionClavePermiso);
clavePermiso.addEventListener('input', (e) => {
    e.target.value = e.target.value.toUpperCase(); // Forzar mayúsculas en tiempo real
});
descripcionPermiso.addEventListener('blur', validacionDescripcion);

// EVENTOS DEL FORMULARIO
FormPermisos.addEventListener('submit', guardaPermiso);

// EVENTOS DE BOTONES
BtnLimpiar.addEventListener('click', limpiarFormulario);
BtnModificar.addEventListener('click', modificaPermiso);

// EVENTOS DE NAVEGACIÓN
BtnVerPermisos.addEventListener('click', () => {
    mostrarTabla();
});

BtnCrearPermiso.addEventListener('click', async () => {
    limpiarFormulario();
    mostrarFormulario('Registrar Permiso');
});

BtnActualizarTabla.addEventListener('click', () => {
    buscaPermiso();
    Swal.fire({
        position: "center",
        icon: "success",
        title: "Tabla actualizada",
        showConfirmButton: false,
        timer: 1000
    });
});

// EVENTOS DEL DATATABLE
datosDeTabla.on('click', '.modificar', llenarFormulario);
datosDeTabla.on('click', '.eliminar', eliminaPermiso);

// INICIALIZAR AL CARGAR LA PÁGINA
document.addEventListener('DOMContentLoaded', async () => {
    mostrarFormulario('Registrar Permiso');
});