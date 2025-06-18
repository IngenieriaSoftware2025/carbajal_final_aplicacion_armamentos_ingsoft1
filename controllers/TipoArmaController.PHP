<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\TiposArmas;
use Model\Usuarios;

class TipoArmaController
{
    public static function index(Router $router)
    {
        $router->render('tipos_armas/index', [], 'layout');
    }

    public static function guardarTipoArma()
    {
        try {
            $validacion = self::validarRequeridos($_POST, ['nombre_tipo']);
            if ($validacion !== true) {
                self::respuestaJSON(0, $validacion, null, 400);
            }

            $datos = self::sanitizarDatos($_POST);

            $tipo = new TiposArmas([
                'nombre_tipo'    => ucfirst(strtolower($datos['nombre_tipo'])),
                'calibre'        => $datos['calibre'] ? strtoupper($datos['calibre']) : null,
                'situacion'      => 1
            ]);

            $validaciones = [
                function ($tipo) {
                    if (strlen($tipo->nombre_tipo) < 3) {
                        return 'El nombre del tipo debe tener al menos 3 caracteres';
                    }
                    return true;
                }
            ];

            $tipo->crearConRespuesta($validaciones);
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al guardar el tipo de arma: ' . $e->getMessage(), null, 500);
        }
    }

    public static function buscarTiposArmas()
    {
        TiposArmas::buscarTodosConRespuesta(
            [
                'id_tipo_arma',
                'nombre_tipo',
                'calibre',
                'fecha_creacion',
                'situacion'
            ],
            "situacion = 1",
            "id_tipo_arma DESC"
        );
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

    // Métodos auxiliares (puedes moverlos a tu clase base si no existen)
    protected static function validarRequeridos($data, $campos)
    {
        foreach ($campos as $campo) {
            if (empty($data[$campo])) {
                return "El campo {$campo} es requerido";
            }
        }
        return true;
    }

    protected static function sanitizarDatos($data)
    {
        // Aquí llamas a tu método de sanitización general, o aplicas filter_var según tipo.
        return array_map('trim', $data);
    }

    protected static function respuestaJSON($status, $msg, $data = null, $code = 200)
    {
        http_response_code($code);
        echo json_encode(['status' => $status, 'message' => $msg, 'data' => $data]);
        exit;
    }
}
