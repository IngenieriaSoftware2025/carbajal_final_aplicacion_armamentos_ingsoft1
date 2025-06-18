<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Rutas;
use Model\Aplicacion;

class RutaController extends ActiveRecord
{
    public static function index(Router $router)
    {
        $router->render('rutas/index', [], 'layout');
    }

    public static function guardarRuta()
    {
        try {
            $validacion = self::validarRequeridos($_POST, ['id_app', 'descripcion']);
            if ($validacion !== true) {
                self::respuestaJSON(0, $validacion, null, 400);
            }

            $datos = self::sanitizarDatos($_POST);

            $ruta = new Rutas([
                'id_app' => filter_var($datos['id_app'], FILTER_SANITIZE_NUMBER_INT),
                'descripcion' => ucfirst(strtolower($datos['descripcion'])),
                'ruta' => ucfirst(strtolower($datos['ruta'])),
                'situacion' => 1
            ]);

            $validaciones = [
                function ($ruta) {
                    if (!Aplicacion::valorExiste('id_app', $ruta->id_app)) {
                        return 'La aplicación no existe';
                    }
                    return true;
                },
                function ($ruta) {
                    if (strlen($ruta->descripcion) < 5) {
                        return 'La descripción debe tener al menos 5 caracteres';
                    }
                    return true;
                }
            ];

            $ruta->crearConRespuesta($validaciones);
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al guardar la ruta: ' . $e->getMessage(), null, 500);
        }
    }

    public static function buscarRutas()
    {
        $tablaPrincipal = \Model\Rutas::$tabla;

        Rutas::buscarConRelacionRespuesta(
            'dgcm_aplicacion',
            'id_app',
            'id_app',
            [
                'nombre_app_lg' => 'nombre_app_lg',
                'nombre_app_md' => 'nombre_app_md',
                'nombre_app_ct' => 'nombre_app_ct'
            ],
            "$tablaPrincipal.situacion = 1",
            "$tablaPrincipal.id_ruta DESC"
        );
    }

    public static function modificarRuta()
    {
        try {
            if (empty($_POST['id_ruta'])) {
                self::respuestaJSON(0, 'ID de ruta requerido', null, 400);
            }

            $id = $_POST['id_ruta'];
            /** @var \Model\Rutas $ruta */
            $ruta = Rutas::find(['id_ruta' => $id]);

            if (!$ruta) {
                self::respuestaJSON(0, 'Ruta no encontrada', null, 404);
            }

            $datos = self::sanitizarDatos($_POST);

            $datosParaSincronizar = [
                'id_app' => isset($datos['id_app']) ? filter_var($datos['id_app'], FILTER_SANITIZE_NUMBER_INT) : $ruta->id_app,
                'ruta' => isset($datos['ruta']) ? ucfirst(strtolower($datos['ruta'])) : $ruta->ruta,
                'descripcion' => isset($datos['descripcion']) ? ucfirst(strtolower($datos['descripcion'])) : $ruta->descripcion,
                'situacion' => isset($datos['situacion']) ? $datos['situacion'] : $ruta->situacion
            ];

            $ruta->sincronizar($datosParaSincronizar);

            $validaciones = [
                function ($ruta) {
                    if (!Aplicacion::valorExiste('id_app', $ruta->id_app)) {
                        return 'La aplicación no existe';
                    }
                    return true;
                },
                function ($ruta) {
                    if (strlen($ruta->descripcion) < 5) {
                        return 'La descripción debe tener al menos 5 caracteres';
                    }
                    return true;
                }
            ];

            $ruta->actualizarConRespuesta($validaciones);
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al modificar la ruta: ' . $e->getMessage(), null, 500);
        }
    }

    public static function eliminarRuta()
    {
        try {
            if (empty($_POST['id_ruta'])) {
                self::respuestaJSON(0, 'ID de ruta requerido', null, 400);
            }

            $ruta = Rutas::find(['id_ruta' => $_POST['id_ruta']]);

            if (!$ruta) {
                self::respuestaJSON(0, 'Ruta no encontrada', null, 404);
            }

            $ruta->sincronizar(['situacion' => 0]);
            $ruta->guardarConRespuesta();
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al eliminar la ruta: ' . $e->getMessage(), null, 500);
        }
    }
}
