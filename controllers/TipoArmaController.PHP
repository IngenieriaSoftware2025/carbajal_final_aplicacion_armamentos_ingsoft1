<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\TiposArmas;
use Model\ActiveRecord;

class TipoArmaController extends ActiveRecord
{
    public static function index(Router $router)
    {
        $router->render('tipo/index', [], 'layout');
    }

    public static function guardarTipoArma()
    {
        try {
            $datos = self::sanitizarDatos($_POST);

            $tipo = new TiposArmas([
                'nombre_tipo' => ucfirst(strtolower($datos['nombre_tipo'])),
                'calibre'     => !empty($datos['calibre']) ? strtoupper($datos['calibre']) : null,
                'situacion'   => 1
            ]);

            $validaciones = [
                function ($obj) {
                    if (empty($obj->nombre_tipo)) {
                        return 'El nombre del tipo de arma es obligatorio.';
                    }
                    return true;
                },
                function ($obj) {
                    if (strlen($obj->nombre_tipo) < 3) {
                        return 'El nombre del tipo debe tener al menos 3 caracteres.';
                    }
                    return true;
                },
                function ($obj) {
                    if (self::valorExiste('nombre_tipo', $obj->nombre_tipo)) {
                        return 'Este tipo de arma ya está registrado.';
                    }
                    return true;
                }
            ];

            $tipo->crearConRespuesta($validaciones);
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error interno al guardar el tipo de arma: ' . $e->getMessage(), null, 500);
        }
    }

    public static function buscarTiposArmas()
    {
        TiposArmas::buscarConRespuesta("situacion = 1", "nombre_tipo, calibre");
    }

    public static function modificarTipoArma()
    {
        try {
            if (empty($_POST['id_tipo_arma'])) {
                self::respuestaJSON(0, 'ID de tipo de arma requerido', null, 400);
            }

            /** @var TiposArmas $tipo */
            $tipo = TiposArmas::find(['id_tipo_arma' => $_POST['id_tipo_arma']]);
            if (!$tipo) {
                self::respuestaJSON(0, 'Tipo de arma no encontrado', null, 404);
            }

            $datos = self::sanitizarDatos($_POST);
            $sincronizar = [];

            if (isset($datos['nombre_tipo'])) {
                $sincronizar['nombre_tipo'] = ucfirst(strtolower($datos['nombre_tipo']));
            }
            if (array_key_exists('calibre', $datos)) {
                $sincronizar['calibre'] = $datos['calibre'] ? strtoupper($datos['calibre']) : null;
            }
            if (isset($datos['situacion'])) {
                $sincronizar['situacion'] = $datos['situacion'];
            }

            $tipo->sincronizar($sincronizar);

            $validaciones = [
                function ($tipo) {
                    if (strlen($tipo->nombre_tipo) < 3) {
                        return 'El nombre del tipo debe tener al menos 3 caracteres';
                    }
                    return true;
                }
            ];

            $tipo->actualizarConRespuesta($validaciones);
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al modificar el tipo de arma: ' . $e->getMessage(), null, 500);
        }
    }

    public static function eliminarTipoArma()
    {
        try {
            if (empty($_POST['id_tipo_arma'])) {
                self::respuestaJSON(0, 'ID de tipo de arma requerido', null, 400);
            }

            /** @var TiposArmas $tipo */
            $tipo = TiposArmas::find(['id_tipo_arma' => $_POST['id_tipo_arma']]);
            if (!$tipo) {
                self::respuestaJSON(0, 'Tipo de arma no encontrado', null, 404);
            }

            $tipo->sincronizar(['situacion' => 0]);
            $tipo->guardarConRespuesta();
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al eliminar el tipo de arma: ' . $e->getMessage(), null, 500);
        }
    }
}
