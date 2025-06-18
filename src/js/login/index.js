import { Dropdown } from "bootstrap";
import Swal from 'sweetalert2';
import { validarFormulario } from '../funciones';

const FormLogin = document.getElementById('FormLogin');
const BtnLogin = document.getElementById('BtnLogin');
const mostrarAyudaBtn = document.getElementById('mostrarAyuda');

const togglePassword = () => {
    const passwordField = document.getElementById('usuario_clave');
    const eyeIcon = document.getElementById('eyeIcon');

    if (passwordField.type === 'password') {
        // Mostrar contraseña
        passwordField.type = 'text';
        eyeIcon.classList.remove('bi-eye');
        eyeIcon.classList.add('bi-eye-slash');
    } else {
        // Ocultar contraseña
        passwordField.type = 'password';
        eyeIcon.classList.remove('bi-eye-slash');
        eyeIcon.classList.add('bi-eye');
    }
};

const mostrarAyuda = () => {
    Swal.fire({
        title: 'Ayuda y Soporte',
        html: `
            <div style="text-align: left;">
                <p><strong>Para obtener acceso al sistema, ingrese como administrador:</strong></p>
                <hr>
                <p><i class="bi bi-telephone-fill"></i> <strong>Teléfono:</strong> 9773-4613</p>
                <p><i class="bi bi-envelope-fill"></i> <strong>Email:</strong> admin@gmail.com</p>
                <p><i class="bi bi-envelope-fill"></i> <strong>Pass:</strong> Pas@2026</p>
                <p><i class="bi bi-clock-fill"></i> <strong>Horario:</strong> 24/7 </p>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Entendido',
        confirmButtonColor: '#007bff',
        background: '#f8f9fa',
        customClass: {
            title: 'custom-title-class',
            htmlContainer: 'custom-text-class'
        }
    });
};

const login = async (e) => {
    e.preventDefault();

    BtnLogin.disabled = true;

    if (!validarFormulario(FormLogin, [''])) {
        Swal.fire({
            title: 'Campos vacíos',
            text: 'Debe llenar todos los campos',
            icon: 'info'
        });
        BtnLogin.disabled = false;
        return;
    }

    try {
        const body = new FormData(FormLogin);
        const url = '/carbajal_final_aplicacion_armamentos_ingsoft1/login';

        const config = {
            method: 'POST',
            body
        };

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        console.log('Respuesta del servidor:', data);
        const { codigo, mensaje } = data;

        if (codigo == 1) {
            await Swal.fire({
                title: 'Exito',
                text: mensaje,
                icon: 'success',
                showConfirmButton: true,
                timer: 1500,
                timerProgressBar: false,
                background: '#e0f7fa',
                customClass: {
                    title: 'custom-title-class',
                    text: 'custom-text-class'
                }

            });

            FormLogin.reset();
            setTimeout(() => {
                location.href = '/carbajal_final_aplicacion_armamentos_ingsoft1/';
            }, 100);
        } else {
            Swal.fire({
                title: '¡Error!',
                text: mensaje,
                icon: 'warning',
                showConfirmButton: true,
                timer: 1500,
                timerProgressBar: false,
                background: '#e0f7fa',
                customClass: {
                    title: 'custom-title-class',
                    text: 'custom-text-class'
                }

            });
        }

    } catch (error) {
        console.log(error);
    }

    BtnLogin.disabled = false;
};

document.addEventListener('DOMContentLoaded', function () {
    const FormLogin = document.getElementById('FormLogin');
    const BtnLogin = document.getElementById('BtnLogin');
    const mostrarAyudaBtn = document.getElementById('mostrarAyuda');
    const togglePasswordBtn = document.getElementById('togglePassword');

    console.log('DOM cargado');

    // Event listener para el formulario de login
    if (FormLogin && BtnLogin) {
        FormLogin.addEventListener('submit', login);
        console.log('Event listener del form agregado');
    }

    // Event listener para el botón de ayuda
    if (mostrarAyudaBtn) {
        mostrarAyudaBtn.addEventListener('click', function (e) {
            e.preventDefault();
            mostrarAyuda();
        });
        console.log('Event listener del botón ayuda agregado');
    }

    // Event listener para mostrar/ocultar contraseña
    if (togglePasswordBtn) {
        togglePasswordBtn.addEventListener('click', function (e) {
            e.preventDefault();
            togglePassword();
        });
        console.log('Event listener del toggle password agregado');
    }
});