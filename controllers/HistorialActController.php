<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\HistorialAct;
use Model\Usuarios;
use Model\Rutas;

class HistorialActController extends ActiveRecord
{
    public $ruta;

    public static function index(Router $router)
    {
        $router->render('historial/index', [], 'layout');
    }

    public static function guardarHistorial()
    {

        try {
            $validacion = self::validarRequeridos($_POST, ['id_usuario', 'ruta', 'ejecucion', 'status']);
            if ($validacion !== true) {
                self::respuestaJSON(0, $validacion, null, 400);
            }

            $datos = self::sanitizarDatos($_POST);

            $historial = new HistorialAct([
                'id_usuario' => filter_var($datos['id_usuario'], FILTER_SANITIZE_NUMBER_INT),
                'ruta' => ucfirst(strtolower($datos['ruta'])),
                'fecha_creacion' => date('Y-m-d H:i'),
                'ejecucion' => ucfirst(strtolower($datos['ejecucion'])),
                'status' => filter_var($datos['status'], FILTER_SANITIZE_NUMBER_INT),
                'situacion' => 1
            ]);

            $validaciones = [
                function ($historial) {
                    if (!Usuarios::valorExiste('id_usuario', $historial->id_usuario)) {
                        return 'El usuario no existe';
                    }
                    return true;
                },
                function ($historial) {
                    if (strlen($historial->ejecucion) < 5) {
                        return 'La descripción de ejecución debe tener al menos 5 caracteres';
                    }
                    return true;
                }
            ];

            $historial->crearConRespuesta($validaciones, '/guarda_historial_ruta');
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al guardar el historial: ' . $e->getMessage(), null, 500);
        }
    }

    public static function buscarHistorial()
    {
        Rutas::buscarConRespuesta("situacion = 1", "usuario, ruta, fecha_creacion, ejecucion, estado");
    }

    public static function modificarHistorial()
    {
        try {
            if (empty($_POST['id_hist_act'])) {
                self::respuestaJSON(0, 'ID de historial requerido', null, 400);
            }

            $id = $_POST['id_hist_act'];
            /** @var \Model\HistorialAct $historial */
            $historial = HistorialAct::find(['id_hist_act' => $id]);

            if (!$historial) {
                self::respuestaJSON(0, 'Registro de historial no encontrado', null, 404);
            }

            $datos = self::sanitizarDatos($_POST);

            $datosParaSincronizar = [
                'id_usuario' => isset($datos['id_usuario']) ? filter_var($datos['id_usuario'], FILTER_SANITIZE_NUMBER_INT) : $historial->id_usuario,
                'id_ruta' => isset($datos['id_ruta']) ? filter_var($datos['id_ruta'], FILTER_SANITIZE_NUMBER_INT) : $historial->id_ruta,
                'ejecucion' => isset($datos['ejecucion']) ? ucfirst(strtolower($datos['ejecucion'])) : $historial->ejecucion,
                'status' => isset($datos['status']) ? filter_var($datos['status'], FILTER_SANITIZE_NUMBER_INT) : $historial->status,
                'situacion' => isset($datos['situacion']) ? $datos['situacion'] : $historial->situacion
            ];

            $historial->sincronizar($datosParaSincronizar);

            $validaciones = [
                function ($historial) {
                    if (!Usuarios::valorExiste('id_usuario', $historial->id_usuario)) {
                        return 'El usuario no existe';
                    }
                    return true;
                },
                function ($historial) {
                    if (!Rutas::valorExiste('id_ruta', $historial->id_ruta)) {
                        return 'La ruta no existe';
                    }
                    return true;
                },
                function ($historial) {
                    if (strlen($historial->ejecucion) < 5) {
                        return 'La descripción de ejecución debe tener al menos 5 caracteres';
                    }
                    return true;
                }
            ];

            $historial->actualizarConRespuesta($validaciones, '/modifica_historial_ruta');
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al modificar el historial: ' . $e->getMessage(), null, 500);
        }
    }

    public static function eliminarHistorial()
    {
        try {
            if (empty($_POST['id_hist_act'])) {
                self::respuestaJSON(0, 'ID de historial requerido', null, 400);
            }

            $historial = HistorialAct::find(['id_hist_act' => $_POST['id_hist_act']]);

            if (!$historial) {
                self::respuestaJSON(0, 'Registro de historial no encontrado', null, 404);
            }

            $historial->sincronizar(['situacion' => 0]);
            $historial->guardarConRespuesta();
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al eliminar el historial: ' . $e->getMessage(), null, 500);
        }
    }
}
