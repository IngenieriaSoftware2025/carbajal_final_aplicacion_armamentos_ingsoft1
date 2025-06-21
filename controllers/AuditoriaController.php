<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Controllers\LoginController;

class AuditoriaController extends ActiveRecord
{
    private const ROL_ADMIN = 'ADMIN';

    public static function mostrarVista(Router $router)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
            header('Location: /carbajal_final_aplicacion_armamentos_ingsoft1/login');
            exit;
        }
        if (!LoginController::tienePermiso(self::ROL_ADMIN)) {
            header('Location: /carbajal_final_aplicacion_armamentos_ingsoft1/inicio');
            exit;
        }

        $router->render('auditoria/dashboard', [], 'layout');
    }

    public static function dashboard()
    {
        // Vemos si hay sesion activa y si el usuario tiene permisos
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
            self::respuestaJSON(0, 'No tienes permiso para acceder a esta página', null, 401);
            return;
        }

        if (!LoginController::tienePermiso(self::ROL_ADMIN)) {
            self::respuestaJSON(0, 'No tienes permiso para acceder a esta página', null, 403);
            return;
        }

        // Confirma los headers de la API
        getHeadersApi();

        try {

            $queryHistorialCompleto = "SELECT 
                        aha.id_hist_act as ID,
                        (u.nombre1 || ' ' || u.apellido1) as Usuario,
                        r.ruta as Ruta,
                        aha.ejecucion as Ejecucion,
                        CASE 
                            WHEN aha.estado = 1 THEN 'Éxito'
                            WHEN aha.estado = 0 THEN 'Error'
                            ELSE 'Desconocido'
                        END as Status,
                        aha.fecha_creacion as Fecha
                    FROM dgcm_auditoria_historial_act aha
                    INNER JOIN dgcm_usuarios u ON aha.id_usuario = u.id_usuario
                    INNER JOIN dgcm_rutas r ON aha.id_ruta = r.id_ruta
                    WHERE aha.situacion = 1
                    ORDER BY aha.fecha_creacion DESC;";

            $historialCompleto = ActiveRecord::fetchArray($queryHistorialCompleto);

            $totalRegistros = count($historialCompleto);
            $usuariosUnicos = count(array_unique(array_column($historialCompleto, 'Usuario')));

            $respuesta = [
                'historial' => $historialCompleto,
                'estadisticas' => [
                    'total_registros' => $totalRegistros,
                    'usuarios_unicos' => $usuariosUnicos
                ]
            ];

            self::respuestaJSON(1, 'Historial obtenido exitosamente', $respuesta);
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al obtener dashboard: ' . $e->getMessage(), null, 500);
        }
    }
}
