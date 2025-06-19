<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Controllers\LoginController;

class GraficasController extends ActiveRecord
{
    // private const ROL_ADMIN = 'ADMIN';

    public static function index(Router $router)
    {
        // if (session_status() === PHP_SESSION_NONE) {
        //     session_start();
        // }
        // if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        //     header('Location: /');
        //     exit;
        // }
        // Solo usuarios con estos roles pueden ver las gráficas
        // if (!LoginController::tienePermiso(self::ROL_ADMIN) ) {
        //     header('Location: /sin-permiso');
        //     exit;
        // }

        $router->render('graficas/index', []);
    }

    public static function obtenerDatosGraficas()
    {
        // Control de sesión y permisos para la API
        // if (session_status() === PHP_SESSION_NONE) session_start();
        // if (empty($_SESSION['login']) || $_SESSION['login'] !== true) {
        //     self::respuestaJSON(0, 'Acceso no autorizado', null, 401);
        //     return;
        // }
        // if (!LoginController::tienePermiso(self::ROL_ADMIN) ) {
        //     self::respuestaJSON(0, 'No tienes permiso para ver esta información', null, 403);
        //     return;
        // }

        try {
            // 1. Gráfica: Cantidad total de armas por tipo
            $sqlArmasPorTipo = "
                SELECT 
                    ta.nombre_tipo,
                    SUM(a.cantidad) AS total_cantidad
                FROM dgcm_armas a
                JOIN dgcm_tipos_armas ta ON a.id_tipo_arma = ta.id_tipo_arma
                WHERE a.situacion = 1 AND ta.situacion = 1
                GROUP BY ta.nombre_tipo
                ORDER BY total_cantidad DESC
            ";
            $armasPorTipo = self::fetchArray($sqlArmasPorTipo);

            // 2. Gráfica: Cantidad de armas por estado
            $sqlArmasPorEstado = "
                SELECT 
                    estado,
                    SUM(cantidad) AS total_cantidad
                FROM dgcm_armas
                WHERE situacion = 1 AND estado IS NOT NULL AND estado <> ''
                GROUP BY estado
                ORDER BY total_cantidad DESC
            ";
            $armasPorEstado = self::fetchArray($sqlArmasPorEstado);

            // 3. Gráfica: Actividad en el sistema 
            $sqlActividad = "
                SELECT 
                    EXTEND(fecha_creacion, YEAR TO DAY) AS fecha,
                    COUNT(*) AS total_acciones
                FROM dgcm_historial_act
                WHERE fecha_creacion >= (TODAY - 30 UNITS DAY)
                GROUP BY fecha
                ORDER BY fecha ASC
            ";
            $actividadReciente = self::fetchArray($sqlActividad);

            // Enviar todos los datos en una sola respuesta JSON
            self::respuestaJSON(1, 'Datos para gráficas obtenidos con éxito', [
                'armasPorTipo' => $armasPorTipo,
                'armasPorEstado' => $armasPorEstado,
                'actividadReciente' => $actividadReciente,
            ]);
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al obtener los datos para las gráficas', ['error' => $e->getMessage()], 500);
        }
    }
}
