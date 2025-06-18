<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Permisos;
use Controllers\LoginController;

class PermisoController extends ActiveRecord
{
    private const ROL_ADMIN = 'ADMIN';

    public static function mostrarPermisos(Router $router)
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

        $router->render('permisos/permisos', [], 'layout');
    }

    public static function guardarPermiso()
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
            // Validar campos requeridos usando helper
            $validacion = self::validarRequeridos($_POST, [
                'nombre_permiso',
                'clave_permiso',
                'descripcion'
            ]);

            if ($validacion !== true) {
                self::respuestaJSON(0, $validacion, null, 400);
            }

            // Sanitizar datos usando helper
            $datos = self::sanitizarDatos($_POST);

            // Crear permiso con validaciones personalizadas
            $permiso = new Permisos([
                'nombre_permiso' => ucwords(strtolower($datos['nombre_permiso'])),
                'clave_permiso' => strtoupper($datos['clave_permiso']), // Clave en mayúsculas
                'descripcion' => ucfirst(strtolower($datos['descripcion'])),
                'fecha_creacion' => date('Y-m-d H:i:s'),
                'situacion' => 1
            ]);

            // Definir validaciones específicas de permiso
            $validaciones = [
                // Validar longitud de campos
                function ($permiso) {
                    if (strlen($permiso->nombre_permiso) < 3) {
                        return 'Nombre del permiso debe tener más de 3 caracteres';
                    }
                    if (strlen($permiso->clave_permiso) < 2) {
                        return 'Clave del permiso debe tener más de 2 caracteres';
                    }
                    if (strlen($permiso->descripcion) < 5) {
                        return 'Descripción debe tener más de 5 caracteres';
                    }
                    return true;
                },

                // Validar longitudes máximas
                function ($permiso) {
                    if (strlen($permiso->nombre_permiso) > 255) {
                        return 'Nombre del permiso no puede exceder 255 caracteres';
                    }
                    if (strlen($permiso->clave_permiso) > 100) {
                        return 'Clave del permiso no puede exceder 100 caracteres';
                    }
                    if (strlen($permiso->descripcion) > 1000) {
                        return 'Descripción no puede exceder 1000 caracteres';
                    }
                    return true;
                },

                // Validar unicidad de la clave del permiso
                function ($permiso) {
                    if (Permisos::valorExiste('clave_permiso', $permiso->clave_permiso)) {
                        return 'La clave del permiso ya está registrada en el sistema';
                    }
                    return true;
                },

                // Validar unicidad del nombre del permiso
                function ($permiso) {
                    if (Permisos::valorExiste('nombre_permiso', $permiso->nombre_permiso)) {
                        return 'El nombre del permiso ya está registrado en el sistema';
                    }
                    return true;
                }
            ];

            // Crear con validaciones y respuesta automática
            $permiso->crearConRespuesta($validaciones);
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al guardar permiso: ' . $e->getMessage(), null, 500);
        }
    }

    public static function buscaPermiso()
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

        Permisos::buscarConRespuesta("situacion = 1", "nombre_permiso");
    }

    public static function modificaPermiso()
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
            if (empty($_POST['id_permiso'])) {
                self::respuestaJSON(0, 'ID de permiso requerido', null, 400);
            }

            $id = $_POST['id_permiso'];

            // Buscar permiso existente
            $permiso = Permisos::find(['id_permiso' => $id]);
            if (!$permiso) {
                self::respuestaJSON(0, 'Permiso no encontrado', null, 404);
            }

            // Sanitizar nuevos datos
            $datos = self::sanitizarDatos($_POST);

            // Preparar TODOS los datos para sincronizar
            $datosParaSincronizar = [
                'nombre_permiso' => ucwords(strtolower($datos['nombre_permiso'])),
                'clave_permiso' => strtoupper($datos['clave_permiso']), // Clave en mayúsculas
                'descripcion' => ucfirst(strtolower($datos['descripcion'])),
                'situacion' => 1
            ];

            // Usar sincronizar una sola vez con todos los datos
            $permiso->sincronizar($datosParaSincronizar);

            // Validaciones excluyendo el registro actual
            $validaciones = [
                function ($permiso) {
                    // Validar longitudes mínimas
                    if (strlen($permiso->nombre_permiso) < 3) return 'Nombre del permiso debe tener más de 3 caracteres';
                    if (strlen($permiso->clave_permiso) < 2) return 'Clave del permiso debe tener más de 2 caracteres';
                    if (strlen($permiso->descripcion) < 10) return 'Descripción debe tener más de 10 caracteres';

                    // Validar longitudes máximas
                    if (strlen($permiso->nombre_permiso) > 255) return 'Nombre del permiso no puede exceder 255 caracteres';
                    if (strlen($permiso->clave_permiso) > 100) return 'Clave del permiso no puede exceder 100 caracteres';
                    if (strlen($permiso->descripcion) > 1000) return 'Descripción no puede exceder 1000 caracteres';

                    // Validar unicidad excluyendo el registro actual
                    if (Permisos::valorExiste('clave_permiso', $permiso->clave_permiso, $permiso->id_permiso, 'id_permiso')) {
                        return 'La clave del permiso ya está en uso por otro permiso';
                    }

                    if (Permisos::valorExiste('nombre_permiso', $permiso->nombre_permiso, $permiso->id_permiso, 'id_permiso')) {
                        return 'El nombre del permiso ya está en uso por otro permiso';
                    }

                    return true;
                }
            ];

            // Actualizar con validaciones automáticas
            $permiso->actualizarConRespuesta($validaciones);
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al modificar permiso: ' . $e->getMessage(), null, 500);
        }
    }

    public static function eliminaPermiso()
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
            if (empty($_POST['id_permiso'])) {
                self::respuestaJSON(0, 'ID de permiso requerido', null, 400);
            }

            // Usamos el helper lógico para eliminar el permiso
            Permisos::eliminarLogicoConRespuesta($_POST['id_permiso'], 'id_permiso');
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al eliminar permiso: ' . $e->getMessage(), null, 500);
        }
    }
}
