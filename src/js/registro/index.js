import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { validarFormulario } from "../funciones";
import { lenguaje } from "../lenguaje";

const FormRegistro = document.getElementById('FormRegistro');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const validarTelefono = document.getElementById('telefono');
const contraseniaBtn = document.getElementById('contraseniaBtn');
const iconOjo = document.getElementById('iconOjo');
const usuarioClave = document.getElementById('usuario_clave');
const confirmarClave = document.getElementById('confirmar_clave');
// Botones de acción
const BtnVerUsuarios = document.getElementById('BtnVerUsuarios');
const BtnCrearUsuario = document.getElementById('BtnCrearUsuario');
const BtnActualizarTabla = document.getElementById('BtnActualizarTabla');

// Secciones del formulario y tabla
const seccionFormulario = document.getElementById('seccionFormulario');
const seccionTabla = document.getElementById('seccionTabla');
const tituloFormulario = document.getElementById('tituloFormulario');

// cambiar vistas
const mostrarFormulario = (titulo = 'Registrar Usuario') => {
    seccionFormulario.classList.remove('d-none');
    seccionTabla.classList.add('d-none');
    tituloFormulario.textContent = titulo;

    // Scroll al formulario
    seccionFormulario.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

// Evento para mostrar el formulario de registro
const mostrarTabla = () => {
    seccionFormulario.classList.add('d-none');
    seccionTabla.classList.remove('d-none');

    // Actualizar datos automáticamente
    buscaUsuario();

    // Scroll a la tabla
    seccionTabla.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

// Validación de teléfono
const validacionTelefono = () => {
    const cantidadDigitos = validarTelefono.value;

    if (cantidadDigitos.length < 1) {
        validarTelefono.classList.remove('is-valid', 'is-invalid');
    } else {
        if (cantidadDigitos.length != 8) {
            Swal.fire({
                position: "center",
                icon: "warning",
                title: "Teléfono incorrecto",
                text: "Ingresa exactamente 8 dígitos",
                showConfirmButton: false,
                timer: 1000
            });
            validarTelefono.classList.remove('is-valid');
            validarTelefono.classList.add('is-invalid');
        } else {
            validarTelefono.classList.remove('is-invalid');
            validarTelefono.classList.add('is-valid');
        }
    }
}

// Validación de DPI
const validarDPI = document.getElementById('dpi');
const validacionDPI = () => {
    const dpi = validarDPI.value;

    if (dpi.length > 0) {
        if (dpi.length != 13) {
            Swal.fire({
                position: "center",
                icon: "warning",
                title: "DPI incorrecto",
                text: "El DPI debe tener exactamente 13 dígitos",
                showConfirmButton: false,
                timer: 1000
            });
            validarDPI.classList.remove('is-valid');
            validarDPI.classList.add('is-invalid');
        } else {
            validarDPI.classList.remove('is-invalid');
            validarDPI.classList.add('is-valid');
        }
    } else {
        validarDPI.classList.remove('is-valid', 'is-invalid');
    }
}

// Validación de contraseña segura
const validarContrasenaSegura = () => {
    const password = usuarioClave.value;
    let errores = [];

    if (password.length < 10) {
        errores.push("Mínimo 10 caracteres");
    }

    if (!/[A-Z]/.test(password)) {
        errores.push("Al menos una mayúscula");
    }

    if (!/[a-z]/.test(password)) {
        errores.push("Al menos una minúscula");
    }

    if (!/[0-9]/.test(password)) {
        errores.push("Al menos un número");
    }

    if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
        errores.push("Al menos un carácter especial");
    }

    if (errores.length > 0) {
        usuarioClave.classList.remove('is-valid');
        usuarioClave.classList.add('is-invalid');

        // Mostrar tooltip con errores
        usuarioClave.title = "Falta: " + errores.join(", ");
        return false;
    } else {
        usuarioClave.classList.remove('is-invalid');
        usuarioClave.classList.add('is-valid');
        usuarioClave.title = "Contraseña segura ✓";
        return true;
    }
}

// Validar que las contraseñas coincidan
const validarCoincidenciaPassword = () => {
    if (confirmarClave.value && usuarioClave.value) {
        if (usuarioClave.value === confirmarClave.value) {
            confirmarClave.classList.remove('is-invalid');
            confirmarClave.classList.add('is-valid');
            return true;
        } else {
            confirmarClave.classList.remove('is-valid');
            confirmarClave.classList.add('is-invalid');
            return false;
        }
    }
    return true;
}

// Toggle para mostrar/ocultar contraseña
const mostrarContrasenia = () => {
    if (usuarioClave.type === 'password') {
        usuarioClave.type = 'text';
        iconOjo.classList.remove('bi-eye');
        iconOjo.classList.add('bi-eye-slash');
    } else {
        usuarioClave.type = 'password';
        iconOjo.classList.remove('bi-eye-slash');
        iconOjo.classList.add('bi-eye');
    }
}

// DataTable
const datosDeTabla = new DataTable('#TableUsuarios', {
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
            data: 'id_usuario',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        {
            title: 'Nombres',
            data: null,
            render: (data, type, row) => {
                return `${row.nombre1} ${row.nombre2 || ''}`.trim();
            }
        },
        {
            title: 'Apellidos',
            data: null,
            render: (data, type, row) => {
                return `${row.apellido1} ${row.apellido2 || ''}`.trim();
            }
        },
        { title: 'N° Teléfono', data: 'telefono' },
        { title: 'N° DPI', data: 'dpi' },
        { title: 'Correo', data: 'correo' },
        {
            title: 'Opciones',
            data: 'id_usuario',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                <div class='d-flex justify-content-center'>
                    <button class='btn btn-warning modificar mx-1' 
                        data-id="${data}" 
                        data-nombre1="${row.nombre1}"  
                        data-nombre2="${row.nombre2 || ''}"
                        data-apellido1="${row.apellido1}"
                        data-apellido2="${row.apellido2 || ''}"  
                        data-telefono="${row.telefono || ''}"   
                        data-dpi="${row.dpi || ''}"
                        data-correo="${row.correo}">
                        <i class='bi bi-pencil-square me-1'></i> Modificar
                    </button>
                    <button class='btn btn-danger eliminar mx-1' 
                        data-id="${data}">
                        <i class="bi bi-trash3 me-1"></i>Eliminar
                    </button>
                </div>
                `;
            }
        },
    ],
});

const registrarHistorial = async (idRuta, descripcion, estado) => {
    try {
        const url = '/carbajal_final_aplicacion_armamentos_ingsoft1/guarda_historial_ruta';

        // Preparamos los datos para enviar en formato de formulario
        const datos = new URLSearchParams();
        datos.append('id_ruta', idRuta);
        datos.append('ejecucion', descripcion);
        datos.append('estado', estado);

        const respuesta = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: datos
        });

        const resultado = await respuesta.json();
        if (resultado.codigo !== 1) {
            // Si falla el historial, solo lo mostramos en la consola para no molestar al usuario
            console.error('No se pudo registrar el historial:', resultado.mensaje);
        }

    } catch (error) {
        console.error('Error de red al registrar historial:', error);
    }
};



// Guardar usuario
const guardaUsuario = async (e) => {
    e.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(FormRegistro, ['id_usuario', 'nombre2', 'apellido2', 'dpi', 'fotografia'])) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Formulario incompleto",
            text: "Complete los campos obligatorios",
            showConfirmButton: false,
            timer: 1000
        });
        BtnGuardar.disabled = false;
        return;
    }

    // Validar contraseña
    if (!validarContrasenaSegura()) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Contraseña insegura",
            text: "La contraseña no cumple con los requisitos de seguridad",
            showConfirmButton: false,
            timer: 2000
        });
        BtnGuardar.disabled = false;
        return;
    }

    // Validar coincidencia de contraseñas
    if (!validarCoincidenciaPassword()) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Error",
            text: "Las contraseñas no coinciden",
            showConfirmButton: false,
            timer: 1000
        });
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(FormRegistro);
    // console.log('=== DATOS QUE SE ENVÍAN ===');
    // for (let [key, value] of body.entries()) {
    //     console.log(`${key}: ${value}`);
    // }

    const url = '/carbajal_final_aplicacion_armamentos_ingsoft1/guarda_usuario';
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
                title: "¡Éxito!",
                text: datos.mensaje,
                showConfirmButton: false,
                timer: 1000
            });

            registrarHistorial(1, 'Creación exitosa de nuevo usuario', 1);

            limpiarFormulario();

            setTimeout(async () => {
                const resultado = await Swal.fire({
                    title: '¿Desea ver los usuarios registrados?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, ver usuarios',
                    cancelButtonText: 'Seguir registrando'
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

    } catch (error) {
        // console.log('=== ERROR COMPLETO ===');
        // console.log(error);
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
}

// Buscar usuarios
const buscaUsuario = async () => {
    const url = '/carbajal_final_aplicacion_armamentos_ingsoft1/busca_usuario';
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
        // console.log('Respuesta cruda del servidor:', textoRespuesta);

        let datos;
        try {
            datos = JSON.parse(textoRespuesta);
        } catch (errorJSON) {
            console.error('Error parseando JSON:', errorJSON);
            console.error('Respuesta del servidor:', textoRespuesta);
            return;
        }

        // console.log('Datos parseados:', datos);

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

// Llenar formulario
const llenarFormulario = (e) => {
    const datos = e.currentTarget.dataset;

    document.getElementById('id_usuario').value = datos.id;
    document.getElementById('nombre1').value = datos.nombre1;
    document.getElementById('nombre2').value = datos.nombre2;
    document.getElementById('apellido1').value = datos.apellido1;
    document.getElementById('apellido2').value = datos.apellido2;
    document.getElementById('telefono').value = datos.telefono;
    document.getElementById('dpi').value = datos.dpi;
    document.getElementById('correo').value = datos.correo;

    // Limpiar contraseñas en modo edición
    document.getElementById('usuario_clave').value = '';
    document.getElementById('confirmar_clave').value = '';

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    mostrarFormulario('Modificar Usuario');
}

// Limpiar formulario
const limpiarFormulario = () => {
    FormRegistro.reset();

    // Limpiar validaciones
    const inputs = FormRegistro.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.classList.remove('is-valid', 'is-invalid');
    });

    // LIMPIAR VISTA PREVIA
    const contenedor = document.getElementById('contenedorVistaPrevia');
    const imagen = document.getElementById('vistaPrevia');
    if (contenedor) {
        contenedor.classList.add('d-none');
        imagen.src = '';
    }


    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');

    //CAMBIAR TÍTULO CUANDO SE LIMPIE
    tituloFormulario.textContent = 'Registrar Usuario';
}

// Modificar usuario
const modificaUsuario = async (e) => {
    e.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(FormRegistro, ['nombre2', 'apellido2', 'dpi', 'usuario_clave', 'confirmar_clave', 'fotografia'])) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Formulario incompleto",
            text: "Complete los campos obligatorios",
            showConfirmButton: false,
            timer: 1000
        });
        BtnModificar.disabled = false;
        return;
    }

    // Si hay nueva contraseña, validarla
    if (usuarioClave.value && !validarContrasenaSegura()) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Contraseña insegura",
            text: "La nueva contraseña no cumple con los requisitos",
            showConfirmButton: false,
            timer: 2000
        });
        BtnModificar.disabled = false;
        return;
    }

    if (usuarioClave.value && !validarCoincidenciaPassword()) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Error",
            text: "Las contraseñas no coinciden",
            showConfirmButton: false,
            timer: 1000
        });
        BtnModificar.disabled = false;
        return;
    }

    const body = new FormData(FormRegistro);
    const url = '/carbajal_final_aplicacion_armamentos_ingsoft1/modifica_usuario';
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
                title: "¡Éxito!",
                text: datos.mensaje
            });

            limpiarFormulario();
            setTimeout(() => {
                mostrarTabla();
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

    } catch (error) {
        console.log(error);
    }
    BtnModificar.disabled = false;
}

// Eliminar usuario
const eliminaUsuario = async (e) => {
    const idUsuario = e.currentTarget.dataset.id;

    const alertaConfirmaEliminar = await Swal.fire({
        position: "center",
        icon: "question",
        title: "¿Estás seguro?",
        text: "El usuario será eliminado del sistema",
        showConfirmButton: true,
        confirmButtonText: "Sí, eliminar",
        confirmButtonColor: "red",
        cancelButtonText: "Cancelar",
        showCancelButton: true
    });

    if (!alertaConfirmaEliminar.isConfirmed) return;

    const body = new FormData();
    body.append('id_usuario', idUsuario);

    try {
        const respuesta = await fetch('/carbajal_final_aplicacion_armamentos_ingsoft1/elimina_usuario', {
            method: 'POST',
            body
        });

        const datos = await respuesta.json();

        if (datos.codigo === 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Eliminado!",
                text: datos.mensaje
            });
            buscaUsuario();
        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: datos.mensaje
            });
        }
    } catch (error) {
        console.log(error);
    }
};

// FUNCIÓN PARA MOSTRAR VISTA PREVIA
const mostrarVistaPrevia = (input) => {
    const archivo = input.files[0];
    const contenedor = document.getElementById('contenedorVistaPrevia');
    const imagen = document.getElementById('vistaPrevia');
    const infoArchivo = document.getElementById('infoArchivo');

    if (archivo) {
        // Validar tamaño (5MB máximo)
        const tamañoMB = archivo.size / (1024 * 1024);
        if (tamañoMB > 5) {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Archivo muy grande",
                text: "La imagen no puede exceder 5MB",
                showConfirmButton: false,
                timer: 2000
            });
            input.value = '';
            return;
        }

        // Validar tipo de archivo
        const tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!tiposPermitidos.includes(archivo.type)) {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Formato no válido",
                text: "Solo se permiten imágenes JPG, PNG o GIF",
                showConfirmButton: false,
                timer: 2000
            });
            input.value = '';
            return;
        }

        // Crear vista previa
        const reader = new FileReader();
        reader.onload = function (e) {
            imagen.src = e.target.result;
            infoArchivo.textContent = `${archivo.name} (${tamañoMB.toFixed(2)} MB)`;
            contenedor.classList.remove('d-none');

            // Animación suave
            contenedor.style.opacity = '0';
            setTimeout(() => {
                contenedor.style.transition = 'opacity 0.3s';
                contenedor.style.opacity = '1';
            }, 10);
        };
        reader.readAsDataURL(archivo);

    } else {
        contenedor.classList.add('d-none');
    }
}

// FUNCIÓN PARA ELIMINAR VISTA PREVIA
const eliminarVistaPrevia = () => {
    const input = document.getElementById('fotografia');
    const contenedor = document.getElementById('contenedorVistaPrevia');
    const imagen = document.getElementById('vistaPrevia');

    input.value = '';
    imagen.src = '';
    contenedor.classList.add('d-none');

    Swal.fire({
        position: "center",
        icon: "info",
        title: "Imagen eliminada",
        showConfirmButton: false,
        timer: 1000
    });
}

// Eventos de fotografia
document.addEventListener('DOMContentLoaded', () => {
    // Event listeners existentes...
    mostrarFormulario('Registrar Usuario');

    const inputFotografia = document.getElementById('fotografia');
    const btnEliminarImagen = document.getElementById('btnEliminarImagen');

    if (inputFotografia) {
        inputFotografia.addEventListener('change', (e) => {
            mostrarVistaPrevia(e.target);
        });
    }

    if (btnEliminarImagen) {
        btnEliminarImagen.addEventListener('click', eliminarVistaPrevia);
    }
});

// Eventos
validarTelefono.addEventListener('blur', validacionTelefono);
validarDPI.addEventListener('blur', validacionDPI);
contraseniaBtn.addEventListener('click', mostrarContrasenia);
usuarioClave.addEventListener('input', validarContrasenaSegura);
confirmarClave.addEventListener('blur', validarCoincidenciaPassword);

// FormRegistro
FormRegistro.addEventListener('submit', guardaUsuario);

// Botones
BtnLimpiar.addEventListener('click', limpiarFormulario);
BtnModificar.addEventListener('click', modificaUsuario);

// datosDeTabla
datosDeTabla.on('click', '.modificar', llenarFormulario);
datosDeTabla.on('click', '.eliminar', eliminaUsuario);

// Eventos de botones de acción
BtnVerUsuarios.addEventListener('click', () => {
    mostrarTabla();
});

BtnCrearUsuario.addEventListener('click', () => {
    limpiarFormulario();
    mostrarFormulario('Registrar Usuario');
});

BtnActualizarTabla.addEventListener('click', () => {
    buscaUsuario();
    Swal.fire({
        position: "center",
        icon: "success",
        title: "Tabla actualizada",
        showConfirmButton: false,
        timer: 1000
    });
});

// Inicializar el formulario al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    mostrarFormulario('Registrar Usuario');
});