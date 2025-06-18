<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Aplicacion;
use Controllers\LoginController;

class AplicacionController extends ActiveRecord
{
    private const ROL_ADMIN = 'ADMIN';

    public static function mostrarAplicaciones(Router $router)
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

        $router->render('aplicaciones/aplicacion', [], 'layout');
    }

    public static function guardarAplicacion()
    {
        // if (session_status() === PHP_SESSION_NONE) {
        //     session_start();
        // }
        // if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        //     self::respuestaJSON(0, 'Acceso no autorizado. Debes iniciar sesión.', null, 401);
        //     return;
        // }
        // if (!LoginController::tienePermiso(self::ROL_ADMIN)) {
        //     self::respuestaJSON(0, 'No tienes permiso para realizar esta acción.', null, 403);
        //     return;
        // }

        try {
            // Validar campos requeridos usando helper
            $validacion = self::validarRequeridos($_POST, [
                'nombre_app_lg',
                'nombre_app_md',
                'nombre_app_ct'
            ]);

            if ($validacion !== true) {
                self::respuestaJSON(0, $validacion, null, 400);
            }

            // Sanitizar datos usando helper
            $datos = self::sanitizarDatos($_POST);

            // Crear aplicación con validaciones personalizadas
            $aplicacion = new Aplicacion([
                'nombre_app_lg' => ucwords(strtolower($datos['nombre_app_lg'])),
                'nombre_app_md' => ucwords(strtolower($datos['nombre_app_md'])),
                'nombre_app_ct' => strtoupper($datos['nombre_app_ct']), // Siglas en mayúsculas
                'fecha_creacion' => date('Y-m-d H:i:s'),
                'situacion' => 1
            ]);

            // Definir validaciones específicas de aplicación
            $validaciones = [
                // Validar longitud de nombres
                function ($aplicacion) {
                    if (strlen($aplicacion->nombre_app_lg) < 10) {
                        return 'Nombre largo debe tener más de 10 caracteres';
                    }
                    if (strlen($aplicacion->nombre_app_md) < 5) {
                        return 'Nombre mediano debe tener más de 5 caracteres';
                    }
                    if (strlen($aplicacion->nombre_app_ct) < 2) {
                        return 'Nombre corto debe tener más de 2 caracteres';
                    }
                    return true;
                },

                // Validar longitudes máximas
                function ($aplicacion) {
                    if (strlen($aplicacion->nombre_app_lg) > 2056) {
                        return 'Nombre largo no puede exceder 2056 caracteres';
                    }
                    if (strlen($aplicacion->nombre_app_md) > 1056) {
                        return 'Nombre mediano no puede exceder 1056 caracteres';
                    }
                    if (strlen($aplicacion->nombre_app_ct) > 255) {
                        return 'Nombre corto no puede exceder 255 caracteres';
                    }
                    return true;
                },

                // Validar unicidad del nombre corto (siglas)
                function ($aplicacion) {
                    if (Aplicacion::valorExiste('nombre_app_ct', $aplicacion->nombre_app_ct)) {
                        return 'Las siglas de la aplicación ya están registradas en el sistema';
                    }
                    return true;
                },

                // Validar unicidad del nombre largo
                function ($aplicacion) {
                    if (Aplicacion::valorExiste('nombre_app_lg', $aplicacion->nombre_app_lg)) {
                        return 'El nombre largo de la aplicación ya está registrado';
                    }
                    return true;
                }
            ];

            // Crear con validaciones y respuesta automática
            $aplicacion->crearConRespuesta($validaciones);
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al guardar aplicación: ' . $e->getMessage(), null, 500);
        }
    }

    public static function buscaAplicacion()
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

        // Usando el helper para buscar aplicaciones activas
        Aplicacion::buscarConRespuesta("situacion = 1", "nombre_app_ct, nombre_app_md, nombre_app_lg");
    }

    public static function modificaAplicacion()
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
            // Validar que llegue el ID
            if (empty($_POST['id_app'])) {
                self::respuestaJSON(0, 'ID de aplicación requerido', null, 400);
                return;
            }

            $id = $_POST['id_app'];

            // Buscar aplicación existente
            $aplicacion = Aplicacion::find(['id_app' => $id]);
            if (!$aplicacion) {
                self::respuestaJSON(0, 'Aplicación no encontrada', null, 404);
                return;
            }

            // Sanitizar nuevos datos
            $datos = self::sanitizarDatos($_POST);

            // Preparar TODOS los datos para sincronizar
            $datosParaSincronizar = [
                'nombre_app_lg' => ucwords(strtolower($datos['nombre_app_lg'])),
                'nombre_app_md' => ucwords(strtolower($datos['nombre_app_md'])),
                'nombre_app_ct' => strtoupper($datos['nombre_app_ct']), // Siglas en mayúsculas
                'situacion' => 1
            ];

            // Usar sincronizar una sola vez con todos los datos
            $aplicacion->sincronizar($datosParaSincronizar);

            // Validaciones excluyendo el registro actual
            $validaciones = [
                function ($aplicacion) {
                    // Validar longitudes mínimas
                    if (strlen($aplicacion->nombre_app_lg) < 10) return 'Nombre largo debe tener más de 10 caracteres';
                    if (strlen($aplicacion->nombre_app_md) < 5) return 'Nombre mediano debe tener más de 5 caracteres';
                    if (strlen($aplicacion->nombre_app_ct) < 2) return 'Nombre corto debe tener más de 2 caracteres';

                    // Validar longitudes máximas
                    if (strlen($aplicacion->nombre_app_lg) > 2056) return 'Nombre largo no puede exceder 2056 caracteres';
                    if (strlen($aplicacion->nombre_app_md) > 1056) return 'Nombre mediano no puede exceder 1056 caracteres';
                    if (strlen($aplicacion->nombre_app_ct) > 255) return 'Nombre corto no puede exceder 255 caracteres';

                    // Validar unicidad excluyendo el registro actual
                    if (Aplicacion::valorExiste('nombre_app_ct', $aplicacion->nombre_app_ct, $aplicacion->id_app, 'id_app')) {
                        return 'Las siglas ya están en uso por otra aplicación';
                    }

                    if (Aplicacion::valorExiste('nombre_app_lg', $aplicacion->nombre_app_lg, $aplicacion->id_app, 'id_app')) {
                        return 'El nombre largo ya está en uso por otra aplicación';
                    }

                    return true;
                }
            ];

            // Actualizar con validaciones automáticas
            $aplicacion->actualizarConRespuesta($validaciones);
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al modificar aplicación: ' . $e->getMessage(), null, 500);
        }
    }

    public static function eliminaAplicacion()
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
            //  Validar que llegue el ID
            if (empty($_POST['id_app'])) {
                self::respuestaJSON(0, 'ID de aplicación requerido', null, 400);
            }

            // Usamos el helper lógico para eliminar la aplicación
            Aplicacion::eliminarLogicoConRespuesta($_POST['id_app'], 'id_app');
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al eliminar aplicación: ' . $e->getMessage(), null, 500);
        }
    }
}
