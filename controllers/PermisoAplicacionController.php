<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\PermisoAplicacion;
use Model\Permisos;
use Model\Aplicacion;
use Controllers\LoginController;

class PermisoAplicacionController extends ActiveRecord
{
    private const ROL_ADMIN = 'ADMIN';

    public static function mostrarVista(Router $router)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
            header('Location: /login');
            exit;
        }
        if (!LoginController::tienePermiso(self::ROL_ADMIN)) {
            header('Location: /sin-permiso');
            exit;
        }

        $router->render('permisos/permiso_aplicacion', [], 'layout');
    }

    public static function guardarRelacion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
            self::respuestaJSON(0, 'Acceso no autorizado. Debes iniciar sesión.', null, 401);
            return;
        }
        if (!LoginController::tienePermiso(self::ROL_ADMIN)) {
            self::respuestaJSON(0, 'No tienes permiso para realizar esta acción.', null, 403);
            return;
        }

        try {
            $validacion = self::validarRequeridos($_POST, ['id_permiso', 'id_app']);

            if ($validacion !== true) {
                self::respuestaJSON(0, $validacion, null, 400);
            }

            $datos = self::sanitizarDatos($_POST);

            $registro = new PermisoAplicacion([
                'id_permiso' => filter_var($datos['id_permiso'], FILTER_SANITIZE_NUMBER_INT),
                'id_app' => filter_var($datos['id_app'], FILTER_SANITIZE_NUMBER_INT),
                'fecha_creacion' => date('Y-m-d H:i:s'),
                'situacion' => 1
            ]);

            $registro->crearConRespuesta();
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al guardar: ' . $e->getMessage(), null, 500);
        }
    }

    public static function buscarRelaciones()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
            self::respuestaJSON(0, 'Acceso no autorizado. Debes iniciar sesión.', null, 401);
            return;
        }
        if (!LoginController::tienePermiso(self::ROL_ADMIN)) {
            self::respuestaJSON(0, 'No tienes permiso para ver esta información.', null, 403);
            return;
        }

        PermisoAplicacion::buscarConRelacionMultiplesRespuesta(
            [
                [
                    'tabla' => 'dgcm_permisos',
                    'alias' => 'p',
                    'llave_local' => 'id_permiso',
                    'llave_foranea' => 'id_permiso',
                    'campos' => [
                        'nombre_permiso' => 'nombre_permiso',
                        'clave_permiso' => 'clave_permiso',
                        'descripcion_permiso' => 'descripcion'
                    ],
                    'tipo' => 'INNER'
                ],
                [
                    'tabla' => 'dgcm_aplicacion',
                    'alias' => 'a',
                    'llave_local' => 'id_app',
                    'llave_foranea' => 'id_app',
                    'campos' => [
                        'nombre_aplicacion' => 'nombre_app_md'
                    ],
                    'tipo' => 'INNER'
                ]
            ],
            "dgcm_permiso_aplicacion.situacion = 1",
            "dgcm_permiso_aplicacion.fecha_creacion DESC"
        );
    }

    public static function eliminarRelacion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
            self::respuestaJSON(0, 'Acceso no autorizado. Debes iniciar sesión.', null, 401);
            return;
        }
        if (!LoginController::tienePermiso(self::ROL_ADMIN)) {
            self::respuestaJSON(0, 'No tienes permiso para realizar esta acción.', null, 403);
            return;
        }

        try {
            if (empty($_POST['id_permiso_app'])) {
                self::respuestaJSON(0, 'ID de relación requerido', null, 400);
            }
            PermisoAplicacion::eliminarLogicoConRespuesta($_POST['id_permiso_app'], 'id_permiso_app');
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al eliminar: ' . $e->getMessage(), null, 500);
        }
    }
}
