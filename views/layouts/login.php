<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="build/js/app.js"></script>
    <link rel="shortcut icon" href="<?= asset('images/cit.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= asset('build/styles.css') ?>">
    <title>Iniciar Sesión - DemoApp</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm" style="background:#ffffff; border-bottom:1px solid #e0e0e0;">
        <div class="container-fluid">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <a class="navbar-brand d-flex align-items-center" href="/carbajal_final_aplicacion_armamentos_ingsoft1/">
                <img src="<?= asset('./images/cit.png') ?>" width="35" alt="cit" class="me-2">
                <span>Sistema de Armamentos</span>
            </a>

            <div class="collapse navbar-collapse" id="navbarToggler">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <button type="button" id="mostrarAyuda" class="btn btn-outline-dark btn-sm me-2">
                            <i class="bi bi-question-circle"></i> Ayuda
                        </button>
                    </li>
                </ul>
            </div>

        </div>
    </nav>

    <div class="progress fixed-bottom" style="height: 6px;">
        <div class="progress-bar progress-bar-animated" style="background: #e60023;" id="bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <div class="container-fluid py-5 mb-4" style="min-height: 85vh">
        <?php echo $contenido; ?>
    </div>

    <div class="container-fluid border-top py-3 bg-light">
        <div class="row justify-content-center text-center">
            <div class="col-12">
                <p style="font-size:xx-small; font-weight: bold;">
                    <i class="bi bi-shield-lock me-1"></i>
                    Sistema Seguro - Comando de Informática y Tecnología, <?= date('Y') ?> &copy;
                </p>
            </div>
        </div>
    </div>

    <script src="<?= asset('build/js/login/index.js') ?>"></script>
</body>

</html>