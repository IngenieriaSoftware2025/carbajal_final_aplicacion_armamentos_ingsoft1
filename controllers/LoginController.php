<?php

namespace Controllers;

use Model\Usuarios;
use MVC\Router;
use Model\Permisos;
use Model\AsigPermisos;
use Model\ActiveRecord;
use Exception;

class LoginController extends ActiveRecord
{
    public static function index(Router $router)
    {
        $router->render('login/index', [], 'login');
    }

    public static function login()
    {
        getHeadersApi();

        try {
            $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
            $clave = htmlspecialchars($_POST['usuario_clave']);

            $queryUsuario = "SELECT id_usuario, nombre1, apellido1, usuario_clave FROM dgcm_usuarios WHERE correo = '$correo' AND situacion = 1";

            $usuario = ActiveRecord::fetchArray($queryUsuario)[0];

            if ($usuario) {
                $passDB = $usuario['usuario_clave'];

                if (password_verify($clave, $passDB)) {
                    session_start();

                    $nombreUsuario = $usuario['nombre1'] . ' ' . $usuario['apellido1'];
                    $_SESSION['user'] = $nombreUsuario;
                    $_SESSION['id_usuario'] = $usuario['id_usuario'];
                    $_SESSION['login'] = true;

                    // Obtener permisos corregido según tu esquema
                    $sqlPermisos = "SELECT p.clave_permiso, p.nombre_permiso, a.nombre_app_ct 
                                FROM dgcm_asig_permisos ap
                                INNER JOIN dgcm_permiso_aplicacion pa ON ap.id_permiso_app = pa.id_permiso_app
                                INNER JOIN dgcm_permisos p ON pa.id_permiso = p.id_permiso
                                INNER JOIN dgcm_aplicacion a ON pa.id_app = a.id_app
                                WHERE ap.id_usuario = {$usuario['id_usuario']} AND ap.situacion = 1";

                    $permisos = ActiveRecord::fetchArray($sqlPermisos);
                    $_SESSION['permisos'] = $permisos;

                    echo json_encode([
                        'codigo' => 1,
                        'mensaje' => 'Usuario logueado exitosamente'
                    ]);
                } else {
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'La contraseña es incorrecta'
                    ]);
                }
            } else {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El usuario no existe'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al intentar loguearse',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function logout()
    {
        session_start();
        $_SESSION = [];
        session_destroy();
        header('Location: /carbajal_final_aplicacion_armamentos_ingsoft1/login');
        exit;
    }

    public static function renderInicio(Router $router)
    {
        session_start();
        if (!isset($_SESSION['login']) || !$_SESSION['login']) {
            header('Location: /carbajal_final_aplicacion_armamentos_ingsoft1/login');
            exit;
        }

        $router->render('pages/index', [
            'usuario' => $_SESSION['user'] ?? '',
            'permisos' => $_SESSION['permisos'] ?? []
        ], 'layout');
    }

    public static function tienePermiso($clavePermiso)
    {
        session_start();
        if (!isset($_SESSION['permisos'])) return false;

        foreach ($_SESSION['permisos'] as $permiso) {
            if ($permiso['clave_permiso'] === $clavePermiso) {
                return true;
            }
        }
        return false;
    }
}
