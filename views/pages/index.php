<!-- Welcome Section -->
<div class="row mb-4">
  <div class="col-12">
    <div class="bg-white rounded-3 p-4 shadow-sm border-0">
      <div class="row align-items-center">
        <div class="col-lg-8">
          <h2 class="fw-bold text-dark mb-2">¡Bienvenido al Sistema Armamento</h2>
        </div>
        <div class="col-lg-4 text-center">
          <img src="./images/cit.png" class="img-fluid" style="max-width: 120px; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));" alt="CIT Logo">
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Quick Actions Grid -->
<div class="row mb-4">
  <div class="col-12">
    <h4 class="fw-semibold text-dark mb-3">Acciones Rápidas</h4>
  </div>
</div>

<div class="row g-3 mb-5">
  <?php if (isset($_SESSION['user'])): ?>
    <!-- Nueva Aplicación -->
    <div class="col-md-6 col-lg-3">
      <a href="/carbajal_final_aplicacion_armamentos_ingsoft1/aplicaciones" class="text-decoration-none">
        <div class="bg-white rounded-3 p-4 h-100 shadow-sm border-0 hover-card">
          <div class="text-center">
            <div class="mb-3">
              <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width: 60px; height: 60px;">
                <i class="bi bi-app-indicator fs-3 text-primary"></i>
              </div>
            </div>
            <h5 class="fw-semibold text-dark mb-2">Aplicaciones</h5>
            <p class="text-muted small mb-0">Gestionar aplicaciones del sistema</p>
          </div>
        </div>
      </a>
    </div>

    <!-- Permisos -->
    <div class="col-md-6 col-lg-3">
      <a href="/carbajal_final_aplicacion_armamentos_ingsoft1/permisos" class="text-decoration-none">
        <div class="bg-white rounded-3 p-4 h-100 shadow-sm border-0 hover-card">
          <div class="text-center">
            <div class="mb-3">
              <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10" style="width: 60px; height: 60px;">
                <i class="bi bi-shield-lock fs-3 text-success"></i>
              </div>
            </div>
            <h5 class="fw-semibold text-dark mb-2">Permisos</h5>
            <p class="text-muted small mb-0">Controlar accesos y permisos</p>
          </div>
        </div>
      </a>
    </div>

    <!-- Usuarios -->
    <div class="col-md-6 col-lg-3">
      <a href="/carbajal_final_aplicacion_armamentos_ingsoft1/registro" class="text-decoration-none">
        <div class="bg-white rounded-3 p-4 h-100 shadow-sm border-0 hover-card">
          <div class="text-center">
            <div class="mb-3">
              <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-info bg-opacity-10" style="width: 60px; height: 60px;">
                <i class="bi bi-people fs-3 text-info"></i>
              </div>
            </div>
            <h5 class="fw-semibold text-dark mb-2">Usuarios</h5>
            <p class="text-muted small mb-0">Administrar usuarios del sistema</p>
          </div>
        </div>
      </a>
    </div>

    <!-- Asignaciones -->
    <div class="col-md-6 col-lg-3">
      <a href="/carbajal_final_aplicacion_armamentos_ingsoft1/asignacion_permisos" class="text-decoration-none">
        <div class="bg-white rounded-3 p-4 h-100 shadow-sm border-0 hover-card">
          <div class="text-center">
            <div class="mb-3">
              <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10" style="width: 60px; height: 60px;">
                <i class="bi bi-person-badge fs-3 text-warning"></i>
              </div>
            </div>
            <h5 class="fw-semibold text-dark mb-2">Asignaciones</h5>
            <p class="text-muted small mb-0">Asignar permisos a usuarios</p>
          </div>
        </div>
      </a>
    </div>
  <?php else: ?>
    <!-- Login -->
    <div class="col-md-6">
      <a href="/carbajal_final_aplicacion_armamentos_ingsoft1/login" class="text-decoration-none">
        <div class="bg-white rounded-3 p-5 h-100 shadow-sm border-0 hover-card">
          <div class="text-center">
            <div class="mb-3">
              <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width: 80px; height: 80px;">
                <i class="bi bi-box-arrow-in-right fs-1 text-primary"></i>
              </div>
            </div>
            <h4 class="fw-bold text-dark mb-2">Iniciar Sesión</h4>
            <p class="text-muted mb-0">Accede a tu cuenta del sistema</p>
          </div>
        </div>
      </a>
    </div>

    <!-- Registro -->
    <div class="col-md-6">
      <a href="/carbajal_final_aplicacion_armamentos_ingsoft1/registro" class="text-decoration-none">
        <div class="bg-white rounded-3 p-5 h-100 shadow-sm border-0 hover-card">
          <div class="text-center">
            <div class="mb-3">
              <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10" style="width: 80px; height: 80px;">
                <i class="bi bi-person-plus fs-1 text-success"></i>
              </div>
            </div>
            <h4 class="fw-bold text-dark mb-2">Crear Cuenta</h4>
            <p class="text-muted mb-0">Regístrate en el sistema</p>
          </div>
        </div>
      </a>
    </div>
  <?php endif; ?>
</div>

<!-- Stats Section (Solo si está logueado) -->
<?php if (isset($_SESSION['user'])): ?>
  <div class="row mb-4">
    <div class="col-12">
      <h4 class="fw-semibold text-dark mb-3">Estadísticas del Sistema</h4>
    </div>
  </div>

  <div class="row g-3 mb-5">
    <div class="col-md-6 col-lg-3">
      <div class="bg-white rounded-3 p-4 shadow-sm border-0">
        <div class="d-flex align-items-center">
          <div class="flex-shrink-0">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width: 50px; height: 50px;">
              <i class="bi bi-app text-primary fs-5"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <h3 class="fw-bold text-primary mb-0" id="totalApps">12</h3>
            <p class="text-muted small mb-0">Aplicaciones</p>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-lg-3">
      <div class="bg-white rounded-3 p-4 shadow-sm border-0">
        <div class="d-flex align-items-center">
          <div class="flex-shrink-0">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10" style="width: 50px; height: 50px;">
              <i class="bi bi-shield-check text-success fs-5"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <h3 class="fw-bold text-success mb-0" id="totalPermisos">8</h3>
            <p class="text-muted small mb-0">Permisos</p>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-lg-3">
      <div class="bg-white rounded-3 p-4 shadow-sm border-0">
        <div class="d-flex align-items-center">
          <div class="flex-shrink-0">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-info bg-opacity-10" style="width: 50px; height: 50px;">
              <i class="bi bi-people text-info fs-5"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <h3 class="fw-bold text-info mb-0" id="totalUsuarios">25</h3>
            <p class="text-muted small mb-0">Usuarios</p>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-lg-3">
      <div class="bg-white rounded-3 p-4 shadow-sm border-0">
        <div class="d-flex align-items-center">
          <div class="flex-shrink-0">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10" style="width: 50px; height: 50px;">
              <i class="bi bi-link-45deg text-warning fs-5"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <h3 class="fw-bold text-warning mb-0" id="totalAsignaciones">45</h3>
            <p class="text-muted small mb-0">Asignaciones</p>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<!-- Recent Activity (Solo si está logueado) -->
<?php if (isset($_SESSION['user'])): ?>
  <div class="row">
    <div class="col-12">
      <div class="bg-white rounded-3 p-4 shadow-sm border-0">
        <h5 class="fw-semibold text-dark mb-3">Actividad Reciente</h5>
        <div class="list-group list-group-flush">
          <div class="list-group-item border-0 px-0">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10" style="width: 40px; height: 40px;">
                  <i class="bi bi-plus-circle text-success"></i>
                </div>
              </div>
              <div class="flex-grow-1 ms-3">
                <p class="mb-1 fw-medium">Nueva aplicación registrada</p>
                <small class="text-muted">Hace 2 horas</small>
              </div>
            </div>
          </div>
          <div class="list-group-item border-0 px-0">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width: 40px; height: 40px;">
                  <i class="bi bi-person-plus text-primary"></i>
                </div>
              </div>
              <div class="flex-grow-1 ms-3">
                <p class="mb-1 fw-medium">Usuario agregado al sistema</p>
                <small class="text-muted">Hace 4 horas</small>
              </div>
            </div>
          </div>
          <div class="list-group-item border-0 px-0">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10" style="width: 40px; height: 40px;">
                  <i class="bi bi-shield-lock text-warning"></i>
                </div>
              </div>
              <div class="flex-grow-1 ms-3">
                <p class="mb-1 fw-medium">Permisos actualizados</p>
                <small class="text-muted">Ayer</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<script src="<?= asset('build/js/inicio.js') ?>"></script>