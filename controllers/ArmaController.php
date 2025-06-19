<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\Armas;
use Model\TiposArmas;
use Model\Usuarios;

class ArmaController
{
    public static function index(Router $router)
    {
        $router->render('armas/index', [], 'layout');
    }

    public static function guardarArma()
    {
        try {
            $validacion = self::validarRequeridos($_POST, ['id_tipo_arma', 'id_usuario', 'cantidad']);
            if ($validacion !== true) {
                self::respuestaJSON(0, $validacion, null, 400);
            }

            $datos = self::sanitizarDatos($_POST);

            $arma = new Armas([
                'id_tipo_arma' => filter_var($datos['id_tipo_arma'], FILTER_SANITIZE_NUMBER_INT),
                'id_usuario' => filter_var($datos['id_usuario'], FILTER_SANITIZE_NUMBER_INT),
                'cantidad' => filter_var($datos['cantidad'], FILTER_SANITIZE_NUMBER_INT),
                'estado' => ucfirst(strtolower($datos['estado'] ?? '')),
                'situacion' => 1
            ]);

            $validaciones = [
                function ($arma) {
                    if (!TiposArmas::valorExiste('id_tipo_arma', $arma->id_tipo_arma)) {
                        return 'El tipo de arma no existe';
                    }
                    return true;
                },
                function ($arma) {
                    if (!Usuarios::valorExiste('id_usuario', $arma->id_usuario)) {
                        return 'El usuario no existe';
                    }
                    return true;
                },
                function ($arma) {
                    if ($arma->cantidad <= 0) {
                        return 'La cantidad debe ser mayor que cero';
                    }
                    return true;
                }
            ];

            $arma->crearConRespuesta($validaciones);
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al guardar el arma: ' . $e->getMessage(), null, 500);
        }
    }

    public static function buscarArmas()
    {

        Armas::buscarConRelacionMultiplesRespuesta(
            [
                [
                    'tabla' => 'dgcm_tipos_armas',
                    'alias' => 'ta',
                    'llave_local' => 'id_tipo_arma',
                    'llave_foranea' => 'id_tipo_arma',
                    'campos' => [
                        'nombre_tipo' => 'nombre_tipo',
                        'calibre' => 'calibre'
                    ],
                    'tipo' => 'INNER'
                ],
                [
                    'tabla' => 'dgcm_usuarios',
                    'alias' => 'u',
                    'llave_local' => 'id_usuario',
                    'llave_foranea' => 'id_usuario',
                    'campos' => [
                        'nombre_usuario' => 'nombre1',
                        'apellido_usuario' => 'apellido1'
                    ],
                    'tipo' => 'INNER'
                ]
            ],
            "dgcm_armas.situacion = 1",
            "dgcm_armas.fecha_registro DESC"
        );
    }

    public static function modificarArma()
    {
        try {
            if (empty($_POST['id_arma'])) {
                self::respuestaJSON(0, 'ID de arma requerido', null, 400);
            }

            /** @var Armas $arma */
            $arma = Armas::find(['id_arma' => $_POST['id_arma']]);
            if (!$arma) {
                self::respuestaJSON(0, 'Arma no encontrada', null, 404);
            }

            $datos = self::sanitizarDatos($_POST);
            $sincronizar = [];

            if (isset($datos['id_tipo_arma'])) {
                $sincronizar['id_tipo_arma'] = filter_var($datos['id_tipo_arma'], FILTER_SANITIZE_NUMBER_INT);
            }
            if (isset($datos['id_usuario'])) {
                $sincronizar['id_usuario'] = filter_var($datos['id_usuario'], FILTER_SANITIZE_NUMBER_INT);
            }
            if (isset($datos['cantidad'])) {
                $sincronizar['cantidad'] = filter_var($datos['cantidad'], FILTER_SANITIZE_NUMBER_INT);
            }
            if (isset($datos['estado'])) {
                $sincronizar['estado'] = ucfirst(strtolower($datos['estado']));
            }
            if (isset($datos['situacion'])) {
                $sincronizar['situacion'] = $datos['situacion'];
            }

            $arma->sincronizar($sincronizar);

            $validaciones = [
                function ($arma) {
                    if (!TiposArmas::valorExiste('id_tipo_arma', $arma->id_tipo_arma)) {
                        return 'El tipo de arma no existe';
                    }
                    return true;
                },
                function ($arma) {
                    if (!Usuarios::valorExiste('id_usuario', $arma->id_usuario)) {
                        return 'El usuario no existe';
                    }
                    return true;
                },
                function ($arma) {
                    if ($arma->cantidad <= 0) {
                        return 'La cantidad debe ser mayor que cero';
                    }
                    return true;
                }
            ];

            $arma->actualizarConRespuesta($validaciones);
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al modificar el arma: ' . $e->getMessage(), null, 500);
        }
    }

    public static function eliminarArma()
    {
        try {
            if (empty($_POST['id_arma'])) {
                self::respuestaJSON(0, 'ID de arma requerido', null, 400);
            }

            /** @var Armas $arma */
            $arma = Armas::find(['id_arma' => $_POST['id_arma']]);
            if (!$arma) {
                self::respuestaJSON(0, 'Arma no encontrada', null, 404);
            }

            $arma->sincronizar(['situacion' => 0]);
            $arma->guardarConRespuesta();
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al eliminar el arma: ' . $e->getMessage(), null, 500);
        }
    }

    // Mismos auxiliares que en TipoArmaController
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
        return array_map('trim', $data);
    }

    protected static function respuestaJSON($status, $msg, $data = null, $code = 200)
    {
        http_response_code($code);
        echo json_encode(['status' => $status, 'message' => $msg, 'data' => $data]);
        exit;
    }
}
