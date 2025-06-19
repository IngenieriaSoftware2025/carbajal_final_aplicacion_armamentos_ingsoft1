<?php
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;
use Controllers\AppController;

// Importa mis clases de Controladores
use Controllers\LoginController;
use Controllers\RegistroController;
use Controllers\AplicacionController;
use Controllers\PermisoController;
use Controllers\AsigPermisosController;
use Controllers\PermisoAplicacionController;
use Controllers\RutaController;
use Controllers\HistorialActController;
use Controllers\ArmaController;
use Controllers\TipoArmaController;

use Controllers\MapaController;
use Controllers\GraficasController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class, 'index']);

// Rutas para Login
$router->get('/login', [LoginController::class, 'index']);
$router->post('/login', [LoginController::class, 'login']);
$router->get('/inicio', [LoginController::class, 'renderInicio']);
$router->get('/logout', [LoginController::class, 'logout']);

// Rutas para el registro de usuario
$router->get('/registro', [RegistroController::class, 'mostrarPaginaRegistro']);
$router->get('/busca_usuario', [RegistroController::class, 'buscaUsuario']);
$router->post('/guarda_usuario', [RegistroController::class, 'guardarUsuario']);
$router->post('/modifica_usuario', [RegistroController::class, 'modificaUsuario']);
$router->post('/elimina_usuario', [RegistroController::class, 'eliminaUsuario']);

// Ruta para aplicaciones
$router->get('/aplicaciones', [AplicacionController::class, 'mostrarAplicaciones']);
$router->get('/busca_aplicacion', [AplicacionController::class, 'buscaAplicacion']);
$router->post('/guarda_aplicacion', [AplicacionController::class, 'guardarAplicacion']);
$router->post('/modifica_aplicacion', [AplicacionController::class, 'modificaAplicacion']);
$router->post('/elimina_aplicacion', [AplicacionController::class, 'eliminaAplicacion']);

// Rutas para los permisos
$router->get('/permisos', [PermisoController::class, 'mostrarPermisos']);
$router->get('/busca_permiso', [PermisoController::class, 'buscaPermiso']);
$router->post('/guarda_permiso', [PermisoController::class, 'guardarPermiso']);
$router->post('/modifica_permiso', [PermisoController::class, 'modificaPermiso']);
$router->post('/elimina_permiso', [PermisoController::class, 'eliminaPermiso']);

// Rutas para los permisos de las aplicaciones
$router->get('/permiso_aplicacion', [PermisoAplicacionController::class, 'mostrarVista']);
$router->get('/busca_permiso_aplicacion', [PermisoAplicacionController::class, 'buscarRelaciones']);
$router->post('/guarda_permiso_aplicacion', [PermisoAplicacionController::class, 'guardarRelacion']);
$router->post('/elimina_permiso_aplicacion', [PermisoAplicacionController::class, 'eliminarRelacion']);

// Rutas para Asignación de Permisos
$router->get('/asignacion_permisos', [AsigPermisosController::class, 'mostrarAsignaciones']);
$router->get('/busca_asig_permiso', [AsigPermisosController::class, 'buscarAsignaciones']);
$router->post('/guarda_asig_permiso', [AsigPermisosController::class, 'guardarAsignacion']);
$router->post('/modifica_asig_permiso', [AsigPermisosController::class, 'modificarAsignacion']);
$router->post('/elimina_asig_permiso', [AsigPermisosController::class, 'eliminarAsignacion']);

// Rutas para rutas
$router->get('/rutas', [RutaController::class, 'index']);
$router->get('/busca_ruta', [RutaController::class, 'buscarRutas']);
$router->post('/guarda_ruta', [RutaController::class, 'guardarRuta']);
$router->post('/modifica_ruta', [RutaController::class, 'modificarRuta']);
$router->post('/elimina_ruta', [RutaController::class, 'eliminarRuta']);

// Rutas para Historial de Rutas
$router->get('/historial', [HistorialActController::class, 'index']);
$router->get('/busca_historial_ruta', [HistorialActController::class, 'buscarHistorial']);
$router->post('/guarda_historial_ruta', [HistorialActController::class, 'guardarHistorial']);
$router->post('/modifica_historial_ruta', [HistorialActController::class, 'modificarHistorial']);

// Rutas para la gestión de armas
$router->get('/armas', [ArmaController::class, 'index']);
$router->get('/busca_arma', [ArmaController::class, 'buscarArmas']);
$router->post('/guarda_arma', [ArmaController::class, 'guardarArma']);
$router->post('/modifica_arma', [ArmaController::class, 'modificarArma']);
$router->post('/elimina_arma', [ArmaController::class, 'eliminarArma']);

// Rutas para la gestión de armas
$router->get('/tipo_armas', [TipoArmaController::class, 'index']);
$router->get('/busca_tipo_arma', [TipoArmaController::class, 'buscarTiposArmas']);
$router->post('/guarda_tipo_arma', [TipoArmaController::class, 'guardarTipoArma']);
$router->post('/modifica_tipo_arma', [TipoArmaController::class, 'modificarTipoArma']);
$router->post('/elimina_tipo_arma', [TipoArmaController::class, 'eliminarTipoArma']);

// Rutas para el Mapa
$router->get('/mapa', [MapaController::class, 'renderizarMapa']);

// Rutas para graficas
$router->get('/graficas', [GraficasController::class, 'index']);
$router->get('/graficas/datos', [GraficasController::class, 'obtenerDatosGraficas']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
