import * as bootstrap from 'bootstrap';
import '../scss/app.scss';
import 'leaflet/dist/leaflet.css';

// Inicialización global de Bootstrap (queda como global para el DOM dinámico)
window.bootstrap = bootstrap;

//Inicializar todos los dropdowns de forma automática y reutilizable
function initializeDropdowns() {
    const dropdownElements = document.querySelectorAll('[data-bs-toggle="dropdown"]');
    dropdownElements.forEach(el => {
        if (!bootstrap.Dropdown.getInstance(el)) {
            new bootstrap.Dropdown(el);
        }
    });
}

// Marcar los links activos en la navegación
function highlightActiveLinks() {
    const currentPath = window.location.pathname;
    document.querySelectorAll('.nav-link, .dropdown-item').forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
            const dropdownToggle = link.closest('.dropdown')?.querySelector('.dropdown-toggle');
            dropdownToggle?.classList.add('active');
        }
    });
}

// Sidebar toggle (para móviles)
window.toggleSidebar = () => {
    document.getElementById('sidebar')?.classList.toggle('active');
};

//  Lógica de inicialización
function fullInitialization() {
    initializeDropdowns();
    highlightActiveLinks();

    const observer = new MutationObserver(() => {
        initializeDropdowns();
    });

    if (document.body) {
        observer.observe(document.body, { childList: true, subtree: true });
    }
}

//  Inicialización en múltiples eventos para robustez
document.addEventListener('DOMContentLoaded', fullInitialization);
window.addEventListener('load', fullInitialization);
setTimeout(fullInitialization, 100);

// Observer para DOM dinámico (por si agregas contenido con JS)

//  Progress bar
document.onreadystatechange = () => {
    const progressBar = document.getElementById('progressBar');
    const progressContainer = document.querySelector('.progress-bar-custom');
    if (!progressBar || !progressContainer) return;

    switch (document.readyState) {
        case "loading":
            progressBar.style.width = '0%';
            progressContainer.style.display = 'block';
            break;
        case "interactive":
            progressBar.style.width = '35%';
            break;
        case "complete":
            progressBar.style.width = '100%';
            setTimeout(() => { progressContainer.style.display = 'none'; }, 1000);
            break;
    }
};
