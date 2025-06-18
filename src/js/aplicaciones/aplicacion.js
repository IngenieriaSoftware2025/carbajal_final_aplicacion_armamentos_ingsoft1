import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { validarFormulario } from "../funciones";
import { lenguaje } from "../lenguaje";

// CONSTANTES DEL FORMULARIO ESPECÍFICAS DE APLICACIÓN
const FormAplicacion = document.getElementById('FormAplicacion');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');

// CAMPOS ESPECÍFICOS DE APLICACIÓN
const nombreLargo = document.getElementById('nombre_app_lg');
const nombreMediano = document.getElementById('nombre_app_md');
const nombreCorto = document.getElementById('nombre_app_ct');

// BOTONES DE ACCIÓN
const BtnVerAplicaciones = document.getElementById('BtnVerAplicaciones');
const BtnCrearAplicacion = document.getElementById('BtnCrearAplicacion');
const BtnActualizarTabla = document.getElementById('BtnActualizarTabla');

// SECCIONES DEL FORMULARIO Y TABLA
const seccionFormulario = document.getElementById('seccionFormulario');
const seccionTabla = document.getElementById('seccionTabla');
const tituloFormulario = document.getElementById('tituloFormulario');

// FUNCIONES PARA CAMBIAR VISTAS
const mostrarFormulario = (titulo = 'Registrar Aplicación') => {
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
    buscaAplicacion();

    // Scroll a la tabla
    seccionTabla.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

// VALIDACIONES ESPECÍFICAS DE APLICACIÓN

// Validación de nombre largo
const validacionNombreLargo = () => {
    const texto = nombreLargo.value.trim();

    if (texto.length < 1) {
        nombreLargo.classList.remove('is-valid', 'is-invalid');
        return;
    }

    if (texto.length < 10) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Nombre muy corto",
            text: "El nombre completo debe tener al menos 10 caracteres",
            showConfirmButton: false,
            timer: 1500
        });
        nombreLargo.classList.remove('is-valid');
        nombreLargo.classList.add('is-invalid');
    } else if (texto.length > 2056) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Nombre muy largo",
            text: "El nombre completo no puede exceder 2056 caracteres",
            showConfirmButton: false,
            timer: 1500
        });
        nombreLargo.classList.remove('is-valid');
        nombreLargo.classList.add('is-invalid');
    } else {
        nombreLargo.classList.remove('is-invalid');
        nombreLargo.classList.add('is-valid');
    }
}

// Validación de nombre mediano
const validacionNombreMediano = () => {
    const texto = nombreMediano.value.trim();

    if (texto.length < 1) {
        nombreMediano.classList.remove('is-valid', 'is-invalid');
        return;
    }

    if (texto.length < 5) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Nombre muy corto",
            text: "El nombre mediano debe tener al menos 5 caracteres",
            showConfirmButton: false,
            timer: 1500
        });
        nombreMediano.classList.remove('is-valid');
        nombreMediano.classList.add('is-invalid');
    } else if (texto.length > 1056) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Nombre muy largo",
            text: "El nombre mediano no puede exceder 1056 caracteres",
            showConfirmButton: false,
            timer: 1500
        });
        nombreMediano.classList.remove('is-valid');
        nombreMediano.classList.add('is-invalid');
    } else {
        nombreMediano.classList.remove('is-invalid');
        nombreMediano.classList.add('is-valid');
    }
}

// Validación de siglas (nombre corto)
const validacionSiglas = () => {
    const texto = nombreCorto.value.trim().toUpperCase();
    nombreCorto.value = texto; // Forzar mayúsculas

    if (texto.length < 1) {
        nombreCorto.classList.remove('is-valid', 'is-invalid');
        return;
    }

    if (texto.length < 2) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Siglas muy cortas",
            text: "Las siglas deben tener al menos 2 caracteres",
            showConfirmButton: false,
            timer: 1500
        });
        nombreCorto.classList.remove('is-valid');
        nombreCorto.classList.add('is-invalid');
    } else if (texto.length > 255) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Siglas muy largas",
            text: "Las siglas no pueden exceder 255 caracteres",
            showConfirmButton: false,
            timer: 1500
        });
        nombreCorto.classList.remove('is-valid');
        nombreCorto.classList.add('is-invalid');
    } else {
        nombreCorto.classList.remove('is-invalid');
        nombreCorto.classList.add('is-valid');
    }
}

// DATATABLE PARA APLICACIONES
const datosDeTabla = new DataTable('#TableAplicaciones', {
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
            data: 'id_app',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        {
            title: 'Siglas',
            data: 'nombre_app_ct',
            width: '10%',
            render: (data, type, row) => {
                return `<span class="badge bg-primary fs-6">${data}</span>`;
            }
        },
        {
            title: 'Nombre Mediano',
            data: 'nombre_app_md',
            width: '25%'
        },
        {
            title: 'Nombre Completo',
            data: 'nombre_app_lg',
            width: '35%',
            render: (data, type, row) => {
                return data.length > 50 ? data.substring(0, 50) + '...' : data;
            }
        },
        {
            title: 'Fecha Creación',
            data: 'fecha_creacion',
            width: '15%',
            render: (data, type, row) => {
                if (data) {
                    const fecha = new Date(data);
                    return fecha.toLocaleDateString('es-ES') + '<br><small class="text-muted">' +
                        fecha.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' }) + '</small>';
                }
                return 'N/A';
            }
        },
        {
            title: 'Opciones',
            data: 'id_app',
            searchable: false,
            orderable: false,
            width: '10%',
            render: (data, type, row, meta) => {
                return `
                <div class='d-flex justify-content-center'>
                    <button class='btn btn-warning btn-sm modificar mx-1' 
                        data-id="${data}" 
                        data-nombre_app_lg="${row.nombre_app_lg}"  
                        data-nombre_app_md="${row.nombre_app_md}"
                        data-nombre_app_ct="${row.nombre_app_ct}"
                        title="Modificar aplicación">
                        <i class='bi bi-pencil-square'></i>
                    </button>
                    <button class='btn btn-danger btn-sm eliminar mx-1' 
                        data-id="${data}"
                        title="Eliminar aplicación">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>
                `;
            }
        },
    ],
});

const insertarRutaEnHistorial = async (ruta) => {
    const body = new FormData();
    body.append('ruta', ruta);

    try {
        const respuesta = await fetch('/carbajal_final_aplicacion_armamentos_ingsoft1/guarda_historial_ruta', {
            method: 'POST',
            body
        });

        const datos = await respuesta.json();
        if (datos.codigo !== 1) {
            console.error('Error al insertar en historial:', datos.mensaje);
        }
    } catch (error) {
        console.error('Error al insertar en historial:', error);
    }
}

// GUARDAR APLICACIÓN
const guardaAplicacion = async (e) => {
    e.preventDefault();
    BtnGuardar.disabled = true;

    // Validar formulario (excluyendo id_app)
    if (!validarFormulario(FormAplicacion, ['id_app'])) {
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
    validacionNombreLargo();
    validacionNombreMediano();
    validacionSiglas();

    // Verificar si hay errores de validación
    const errores = FormAplicacion.querySelectorAll('.is-invalid');
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

    const body = new FormData(FormAplicacion);
    // console.log('=== DATOS DE APLICACIÓN QUE SE ENVÍAN ===');
    // for (let [key, value] of body.entries()) {
    //     console.log(`${key}: ${value}`);
    // }

    const url = '/carbajal_final_aplicacion_armamentos_ingsoft1/guarda_aplicacion';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);

        // console.log('=== RESPUESTA DEL SERVIDOR ===');
        // console.log('Status:', respuesta.status);
        // console.log('StatusText:', respuesta.statusText);

        const datos = await respuesta.json();
        // console.log('Datos recibidos:', datos);

        if (datos.codigo === 1) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Aplicación registrada!",
                text: datos.mensaje,
                showConfirmButton: false,
                timer: 1500
            });

            limpiarFormulario();
            insertarRutaEnHistorial('/guarda_aplicacion');

            setTimeout(async () => {
                const resultado = await Swal.fire({
                    title: '¿Desea ver las aplicaciones registradas?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, ver aplicaciones',
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

// BUSCAR APLICACIONES
const buscaAplicacion = async () => {
    const url = '/carbajal_final_aplicacion_armamentos_ingsoft1/busca_aplicacion';
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
const llenarFormulario = (e) => {
    const datos = e.currentTarget.dataset;

    document.getElementById('id_app').value = datos.id;
    document.getElementById('nombre_app_lg').value = datos.nombre_app_lg;
    document.getElementById('nombre_app_md').value = datos.nombre_app_md;
    document.getElementById('nombre_app_ct').value = datos.nombre_app_ct;

    // Limpiar validaciones previas
    const inputs = FormAplicacion.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.classList.remove('is-valid', 'is-invalid');
    });

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    mostrarFormulario('Modificar Aplicación');
}

// LIMPIAR FORMULARIO
const limpiarFormulario = () => {
    FormAplicacion.reset();

    // Limpiar validaciones
    const inputs = FormAplicacion.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.classList.remove('is-valid', 'is-invalid');
    });

    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');

    // Cambiar título cuando se limpie
    tituloFormulario.textContent = 'Registrar Aplicación';
}

// MODIFICAR APLICACIÓN
const modificaAplicacion = async (e) => {
    e.preventDefault();
    BtnModificar.disabled = true;

    // Validar formulario
    if (!validarFormulario(FormAplicacion, [])) {
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
    validacionNombreLargo();
    validacionNombreMediano();
    validacionSiglas();

    // Verificar si hay errores de validación
    const errores = FormAplicacion.querySelectorAll('.is-invalid');
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

    const body = new FormData(FormAplicacion);
    const url = '/carbajal_final_aplicacion_armamentos_ingsoft1/modifica_aplicacion';
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
                title: "¡Aplicación actualizada!",
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

// ELIMINAR APLICACIÓN
const eliminaAplicacion = async (e) => {
    const idAplicacion = e.currentTarget.dataset.id;

    const alertaConfirmaEliminar = await Swal.fire({
        position: "center",
        icon: "question",
        title: "¿Estás seguro?",
        text: "La aplicación será eliminada del sistema",
        showConfirmButton: true,
        confirmButtonText: "Sí, eliminar",
        confirmButtonColor: "#d33",
        cancelButtonText: "Cancelar",
        showCancelButton: true
    });

    if (!alertaConfirmaEliminar.isConfirmed) return;

    const body = new FormData();
    body.append('id_app', idAplicacion);

    try {
        const respuesta = await fetch('/carbajal_final_aplicacion_armamentos_ingsoft1/elimina_aplicacion', {
            method: 'POST',
            body
        });

        const datos = await respuesta.json();

        if (datos.codigo === 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Eliminada!",
                text: datos.mensaje,
                showConfirmButton: false,
                timer: 1500
            });
            buscaAplicacion();
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
nombreLargo.addEventListener('blur', validacionNombreLargo);
nombreMediano.addEventListener('blur', validacionNombreMediano);
nombreCorto.addEventListener('blur', validacionSiglas);
nombreCorto.addEventListener('input', (e) => {
    e.target.value = e.target.value.toUpperCase(); // Forzar mayúsculas en tiempo real
});

// EVENTOS DEL FORMULARIO
FormAplicacion.addEventListener('submit', guardaAplicacion);

// EVENTOS DE BOTONES
BtnLimpiar.addEventListener('click', limpiarFormulario);
BtnModificar.addEventListener('click', modificaAplicacion);

// EVENTOS DE NAVEGACIÓN
BtnVerAplicaciones.addEventListener('click', () => {
    mostrarTabla();
});

BtnCrearAplicacion.addEventListener('click', () => {
    limpiarFormulario();
    mostrarFormulario('Registrar Aplicación');
});

BtnActualizarTabla.addEventListener('click', () => {
    buscaAplicacion();
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
datosDeTabla.on('click', '.eliminar', eliminaAplicacion);

// INICIALIZAR AL CARGAR LA PÁGINA
document.addEventListener('DOMContentLoaded', () => {
    mostrarFormulario('Registrar Aplicación');
});