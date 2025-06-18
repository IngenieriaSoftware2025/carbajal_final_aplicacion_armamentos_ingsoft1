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
    public static function index(Router $router)
    {
        $router->render('historial/index', [], 'layout');
    }

    public static function guardarHistorial()
    {
        try {
            $validacion = self::validarRequeridos($_POST, ['id_usuario', 'id_ruta', 'ejecucion', 'status']);
            if ($validacion !== true) {
                self::respuestaJSON(0, $validacion, null, 400);
            }

            $datos = self::sanitizarDatos($_POST);

            $historial = new HistorialAct([
                'id_usuario' => filter_var($datos['id_usuario'], FILTER_SANITIZE_NUMBER_INT),
                'id_ruta' => filter_var($datos['id_ruta'], FILTER_SANITIZE_NUMBER_INT),
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

            $historial->crearConRespuesta($validaciones);
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al guardar el historial: ' . $e->getMessage(), null, 500);
        }
    }

    public static function buscarHistorial()
    {
        HistorialAct::buscarConRelacionMultiplesRespuesta(
            [
                [
                    'tabla' => 'usuarios',
                    'alias' => 'u',
                    'llave_local' => 'id_usuario',
                    'llave_foranea' => 'id_usuario',
                    'campos' => [
                        'nombre_usuario' => 'nombre1',
                        'apellido_usuario' => 'apellido1',
                        'dpi_usuario' => 'dpi'
                    ],
                    'tipo' => 'INNER'
                ],
                [
                    'tabla' => 'rutas',
                    'alias' => 'r',
                    'llave_local' => 'id_ruta',
                    'llave_foranea' => 'id_ruta',
                    'campos' => [
                        'descripcion_ruta' => 'descripcion'
                    ],
                    'tipo' => 'INNER'
                ],
                [
                    'tabla' => 'aplicacion',
                    'alias' => 'a',
                    'from' => 'r',
                    'llave_local' => 'id_app',
                    'llave_foranea' => 'id_app',
                    'campos' => [
                        'nombre_aplicacion' => 'nombre_app_md'
                    ],
                    'tipo' => 'INNER'
                ]
            ],
            "historial_act.situacion = 1",
            "historial_act.fecha_creacion DESC"
        );
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

            $historial->actualizarConRespuesta($validaciones);
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
