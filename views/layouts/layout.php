<?php
// Iniciar la sesión al principio si no se ha hecho globalmente.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Necesitamos acceso a la clase LoginController para usar su método estático
use Controllers\LoginController;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= asset('images/cit.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= asset('build/styles.css') ?>">
    <title>Sistema de Gestión</title>
</head>

<body>
    <!-- Mobile Toggle -->
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </button>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <!-- Brand -->
        <div class="sidebar-brand">
            <img src="<?= asset('./images/cit.png') ?>" alt="CIT">
            <h5>Sistema</h5>
        </div>

        <!-- User Section -->
        <div class="user-section">
            <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true && isset($_SESSION['user'])) : ?>
                <div class="user-dropdown" id="userDropdown">
                    <button class="user-btn dropdown-toggle" data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            <?= strtoupper(substr($_SESSION['user'], 0, 1)) ?>
                        </div>
                        <span class="flex-grow-1 text-start"><?= htmlspecialchars($_SESSION['user']) ?></span>
                    </button>

                    <div class="dropdown-menu mt-2">
                        <div class="dropdown-header" style="padding: 8px 12px; font-size: 12px; color: #666; border-bottom: 1px solid #e0e0e0;">
                            Sesión Activa
                        </div>
                        <a class="dropdown-item" href="/carbajal_final_aplicacion_armamentos_ingsoft1/logout" style="color: #e60023; padding: 8px 12px;">
                            <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                        </a>
                    </div>
                </div>
            <?php else : ?>
                <a href="/carbajal_final_aplicacion_armamentos_ingsoft1/login" class="btn btn-primary w-100">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                </a>
            <?php endif; ?>
        </div>

        <div class="nav-items">
            <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true) :
                // Definimos las claves de permiso principales
                define('ROL_ADMIN', 'ADMIN');
                define('ROL_ASISTENTE', 'ASISTENTE');
            ?>
                <div class="nav-item">
                    <a class="nav-link <?= ($pagina == 'inicio') ? 'active' : '' ?>" href="/carbajal_final_aplicacion_armamentos_ingsoft1" data-tooltip="Inicio">
                        <i class="bi bi-house-fill"></i>
                        <span>Inicio</span>
                    </a>
                </div>

                <!-- SECCIONES PARA ASISTENTE Y ADMIN -->
                <?php if (LoginController::tienePermiso(ROL_ADMIN) || LoginController::tienePermiso(ROL_ASISTENTE)) : ?>
                    <!-- Dropdown de Gestión Tienda (Rutas, etc) -->
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                            <i class="bi bi-shop"></i>
                            <span>Gestiones de sistema</span>
                        </a>
                        <ul class="dropdown-menu">

                            <?php if (LoginController::tienePermiso(ROL_ADMIN)) : // Solo Admin gestiona usuarios del sistema 
                            ?>
                                <li><a class="dropdown-item" href="/carbajal_final_aplicacion_armamentos_ingsoft1/registro">Usuarios Sistema</a></li>
                                <li><a class="dropdown-item" href="/carbajal_final_aplicacion_armamentos_ingsoft1/tipo_armas">Tipos de Armas</a></li>
                                <li><a class="dropdown-item" href="/carbajal_final_aplicacion_armamentos_ingsoft1/armas">Armas</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div class="nav-item">
                        <a class="nav-link" href="/carbajal_final_aplicacion_armamentos_ingsoft1/graficas" data-tooltip="Gráficas">
                            <i class="bi bi-graph-up"></i>
                            <span>Gráficas/Reportes</span>
                        </a>
                    </div>
                <?php endif; ?>


                <!-- SECCIONES SOLO PARA ADMIN -->
                <?php if (LoginController::tienePermiso(ROL_ADMIN)) : ?>
                    <div class="nav-item">
                        <a class="nav-link" href="/carbajal_final_aplicacion_armamentos_ingsoft1/mapa" data-tooltip="Mapa">
                            <i class="bi bi-geo-alt"></i>
                            <span>Mapa</span>
                        </a>
                    </div>

                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                            <i class="bi bi-gear"></i>
                            <span>Administrador</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/carbajal_final_aplicacion_armamentos_ingsoft1/rutas">Rutas</a></li>
                            <li><a class="dropdown-item" href="/carbajal_final_aplicacion_armamentos_ingsoft1/permisos">Definir Permisos Base</a></li>
                            <li><a class="dropdown-item" href="/carbajal_final_aplicacion_armamentos_ingsoft1/aplicaciones">Aplicaciones</a></li>
                            <li><a class="dropdown-item" href="/carbajal_final_aplicacion_armamentos_ingsoft1/permiso_aplicacion">Permisos por Módulo</a></li>
                            <li><a class="dropdown-item" href="/carbajal_final_aplicacion_armamentos_ingsoft1/asignacion_permisos">Asignar Roles a Usuarios</a></li>
                            <li><a class="dropdown-item" href="/carbajal_final_aplicacion_armamentos_ingsoft1/auditoria">Auditoría del Sistema</a></li>
                        </ul>
                    </div>
                <?php endif; ?>

            <?php endif; // Fin de if (isset($_SESSION['login'])) 
            ?>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content-area">
            <?php echo $contenido; ?>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="progress-bar-custom">
        <div class="progress-fill" id="progressBar"></div>
    </div>

    <script src="<?= asset('build/js/app.js') ?>"></script>

    <!-- Layoout solo asi me carga los dropdowns -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Inicializar todos los dropdowns
            const dropdownElementList = document.querySelectorAll('[data-bs-toggle="dropdown"]');
            const dropdownList = [...dropdownElementList].map(dropdownToggleEl => new bootstrap.Dropdown(dropdownToggleEl));

            // Función para el toggle del sidebar
            window.toggleSidebar = function() {
                const sidebar = document.getElementById('sidebar');
                if (sidebar) {
                    sidebar.classList.toggle('active');
                }
            };

            // Marcar enlaces activos
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link, .dropdown-item');

            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');

                    if (link.classList.contains('dropdown-item')) {
                        const dropdownToggle = link.closest('.dropdown').querySelector('.dropdown-toggle');
                        if (dropdownToggle) {
                            dropdownToggle.classList.add('active');
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>