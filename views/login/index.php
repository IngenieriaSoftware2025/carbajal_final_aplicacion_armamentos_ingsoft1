<div class="row justify-content-center p-3">
    <div class="col-lg-6 col-md-8">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-12 text-center">
                        <i class="bi bi-shield-lock display-1 text-primary mb-3"></i>
                        <h3 class="text-primary mb-2">Iniciar Sesión</h3>
                        <h6 class="text-muted">Ingrese sus credenciales para acceder al sistema</h6>
                    </div>
                </div>

                <div class="row justify-content-center p-3">
                    <form id="FormLogin">
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="correo" class="form-label">
                                    <i class="bi bi-envelope me-1"></i>Correo Electrónico
                                </label>
                                <input type="email" class="form-control" id="correo" name="correo" placeholder="ejemplo@empresa.com" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="usuario_clave" class="form-label">
                                    <i class="bi bi-lock me-1"></i>Contraseña
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="usuario_clave" name="usuario_clave" placeholder="Ingrese su contraseña" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye" id="eyeIcon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="alertasLogin" class="mb-3"></div>

                        <div class="row justify-content-center mt-4">
                            <div class="col-12">
                                <button class="btn btn-primary w-100" type="submit" id="BtnLogin">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                                </button>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <small class="text-muted">
                                    ¿Olvidó su contraseña?
                                    admin@gmail.com
                                    Pas@2026
                                </small>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>