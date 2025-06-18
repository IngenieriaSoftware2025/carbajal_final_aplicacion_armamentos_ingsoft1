<?php

namespace Controllers;

use Exception;
use Model\Usuarios;
use Model\ActiveRecord;
use MVC\Router;
use Model\HistorialAct;
use Model\AsigPermisos;
use Model\PermisoAplicacion;
use Model\Permisos;
use Model\Aplicacion;
use Controllers\LoginController;


class RegistroController extends ActiveRecord
{
    private const ROL_ADMIN = 'ADMIN';

    public static function mostrarPaginaRegistro(Router $router)
    {
        $router->render('registro/index', [], 'layout');
    }

    public static function buscaUsuario()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
            self::respuestaJSON(0, 'Acceso no autorizado. Debes iniciar sesión.', null, 401);
            return;
        }
        // Solo ADMIN puede buscar/listar todos los usuarios del sistema
        if (!LoginController::tienePermiso(self::ROL_ADMIN)) {
            self::respuestaJSON(0, 'No tienes permiso para ver esta información.', null, 403);
            return;
        }

        // usando helper de ActiveRecord
        Usuarios::buscarConRespuesta("situacion = 1", "nombre1, apellido1");
    }

    public static function guardarUsuario()
    {
        try {
            // Validar campos requeridos usando helper
            $validacion = self::validarRequeridos($_POST, [
                'nombre1',
                'apellido1',
                'telefono',
                'correo',
                'usuario_clave'
            ]);

            if ($validacion !== true) {
                self::respuestaJSON(0, $validacion, null, 400);
            }

            // Sanitizar datos usando helper
            $datos = self::sanitizarDatos($_POST);

            // Crear usuario con validaciones personalizadas
            $usuario = new Usuarios([
                'nombre1' => ucwords(strtolower($datos['nombre1'])),
                'nombre2' => ucwords(strtolower($datos['nombre2'] ?? '')),
                'apellido1' => ucwords(strtolower($datos['apellido1'])),
                'apellido2' => ucwords(strtolower($datos['apellido2'] ?? '')),
                'telefono' => filter_var($datos['telefono'], FILTER_SANITIZE_NUMBER_INT),
                'dpi' => filter_var($datos['dpi'] ?? '', FILTER_SANITIZE_NUMBER_INT),
                'correo' => filter_var($datos['correo'], FILTER_SANITIZE_EMAIL),
                'usuario_clave' => password_hash($datos['usuario_clave'], PASSWORD_BCRYPT, ['cost' => 10]),
                'token' => uniqid('user_', true),
                'fecha_creacion' => date('Y-m-d H:i:s'),
                'fecha_clave' => date('Y-m-d H:i:s'),
                'fotografia' => null,
                'situacion' => 1
            ]);

            // PROCESAR FOTOGRAFIA CON DPI COMO NOMBRE
            if (isset($_FILES['fotografia']) && !empty($_FILES['fotografia']['tmp_name'])) {
                // Verificar que tenga DPI para usar como nombre
                if (empty($datos['dpi'])) {
                    self::respuestaJSON(0, 'Se requiere DPI para subir fotografía', null, 400);
                    return;
                }

                $resultadoImagen = self::subirImagen($_FILES['fotografia'], 'usuarios', 2097152, $datos['dpi']);

                if ($resultadoImagen['success']) {
                    $usuario->fotografia = $resultadoImagen['ruta'];
                } else {
                    self::respuestaJSON(0, $resultadoImagen['mensaje'], null, 400);
                    return;
                }
            }

            // Definir validaciones específicas de usuario
            $validaciones = [
                function ($usuario) {
                    if (strlen($usuario->nombre1) < 2) return 'El primer nombre debe tener más de 2 caracteres';
                    if (strlen($usuario->apellido1) < 2) return 'El primer apellido debe tener más de 2 caracteres';
                    if (strlen($usuario->telefono) != 8) return 'El teléfono debe tener exactamente 8 dígitos';
                    if (!filter_var($usuario->correo, FILTER_VALIDATE_EMAIL)) return 'El correo electrónico no es válido';
                    if (!empty($usuario->dpi) && strlen($usuario->dpi) != 13) return 'El DPI debe tener exactamente 13 dígitos';
                    if (Usuarios::valorExiste('correo', $usuario->correo)) return 'El correo ya está registrado';
                    if (Usuarios::valorExiste('telefono', $usuario->telefono)) return 'El teléfono ya está registrado';
                    if (!empty($usuario->dpi) && Usuarios::valorExiste('dpi', $usuario->dpi)) return 'El DPI ya está registrado';
                    return true;
                }

            ];

            // Ejecutar validaciones primero
            foreach ($validaciones as $validacionFunc) {
                $resultadoValidacion = $validacionFunc($usuario);
                if ($resultadoValidacion !== true) {
                    self::respuestaJSON(0, $resultadoValidacion, null, 400);
                    return;
                }
            }

            $resultado = $usuario->crear();

            if ($resultado && $resultado['resultado']) {

                $sqlHist = "
                    INSERT INTO dgcm_historial_act (
                        usuario, ruta, fecha_creacion, ejecucion, estado, situacion
                    ) VALUES (
                        :usuario, :ruta, CURRENT YEAR TO MINUTE, :ejecucion, :estado, 1
                    )
                ";

                $stmt = self::$db->prepare($sqlHist);
                $stmt->bindValue(':usuario',   $_SESSION['nombre_completo'] ?? 'Sistema');

                $stmt->bindValue(':ruta', '/guarda_usuario');
                $stmt->bindValue(':ejecucion', 'Usuario creado con éxito');
                $stmt->bindValue(':estado',    1, \PDO::PARAM_INT);
                $stmt->execute();

                self::respuestaJSON(1, 'Usuario creado exitosamente');
            } else {

                self::respuestaJSON(0, 'Error al guardar el usuario');
            }
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al guardar usuario: ' . $e->getMessage(), null, 500);
        }
    }

    public static function modificaUsuario()
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
            self::respuestaJSON(0, 'Acceso no autorizado. Debes iniciar sesión.', null, 401);
            return;
        }
        if (!LoginController::tienePermiso(self::ROL_ADMIN)) {
            self::respuestaJSON(0, 'No tienes permiso para modificar usuarios.', null, 403);
            return;
        }

        try {
            // Validar que llegue el ID
            if (empty($_POST['id_usuario'])) {
                self::respuestaJSON(0, 'ID de usuario requerido', null, 400);
            }

            $id = $_POST['id_usuario'];

            // Buscar usuario existente
            /** @var \Model\Usuarios $usuario */
            $usuario = Usuarios::find(['id_usuario' => $id]);
            if (!$usuario) {
                self::respuestaJSON(0, 'Usuario no encontrado', null, 404);
            }

            // Sanitizar nuevos datos
            $datos = self::sanitizarDatos($_POST);

            // PROCESAR NUEVA FOTOGRAFIA SI SE SUBIÓ
            if (isset($_FILES['fotografia']) && !empty($_FILES['fotografia']['tmp_name'])) {

                // Eliminar imagen anterior si existe
                if (!empty($usuario->fotografia)) {
                    self::eliminarImagen($usuario->fotografia);
                }

                // Subir nueva imagen con DPI como nombre
                $resultadoImagen = self::subirImagen($_FILES['fotografia'], 'usuarios', 2097152, $usuario->dpi);

                if ($resultadoImagen['success']) {
                    $usuario->fotografia = $resultadoImagen['ruta'];
                } else {
                    self::respuestaJSON(0, $resultadoImagen['mensaje'], null, 400);
                    return;
                }
            }

            // Actualizar propiedades
            $datosParaSincronizar = [
                'nombre1' => ucwords(strtolower($datos['nombre1'])),
                'nombre2' => ucwords(strtolower($datos['nombre2'] ?? '')),
                'apellido1' => ucwords(strtolower($datos['apellido1'])),
                'apellido2' => ucwords(strtolower($datos['apellido2'] ?? '')),
                'telefono' => filter_var($datos['telefono'], FILTER_SANITIZE_NUMBER_INT),
                'dpi' => filter_var($datos['dpi'] ?? '', FILTER_SANITIZE_NUMBER_INT),
                'correo' => filter_var($datos['correo'], FILTER_SANITIZE_EMAIL),
                'fotografia' => $usuario->fotografia,
            ];

            // Si hay nueva contraseña, actualizarla
            if (!empty($datos['usuario_clave'])) {
                $datosParaSincronizar['usuario_clave'] = password_hash($datos['usuario_clave'], PASSWORD_BCRYPT, ['cost' => 10]);
                $datosParaSincronizar['fecha_clave'] = date('Y-m-d H:i:s');
            }

            // Sincronizar datos al usuario
            $usuario->sincronizar($datosParaSincronizar);

            $validaciones = [
                function ($usuario) {
                    if (strlen($usuario->nombre1) < 2) return 'El primer nombre debe tener más de 2 caracteres';
                    if (strlen($usuario->apellido1) < 2) return 'El primer apellido debe tener más de 2 caracteres';
                    if (strlen($usuario->telefono) != 8) return 'El teléfono debe tener exactamente 8 dígitos';
                    if (!filter_var($usuario->correo, FILTER_VALIDATE_EMAIL)) return 'El correo electrónico no es válido';
                    if (!empty($usuario->dpi) && strlen($usuario->dpi) != 13) return 'El DPI debe tener exactamente 13 dígitos';
                    if (Usuarios::valorExiste('correo', $usuario->correo, $usuario->id_usuario, 'id_usuario')) return 'El correo ya está en uso';
                    if (Usuarios::valorExiste('telefono', $usuario->telefono, $usuario->id_usuario, 'id_usuario')) return 'El teléfono ya está en uso';
                    if (!empty($usuario->dpi) && Usuarios::valorExiste('dpi', $usuario->dpi, $usuario->id_usuario, 'id_usuario')) return 'El DPI ya está en uso';
                    return true;
                }
            ];

            // Actualizar con validaciones automáticas
            $usuario->actualizarConRespuesta($validaciones, '/modifica_usuario');
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al modificar usuario: ' . $e->getMessage(), null, 500);
        }
    }

    public static function eliminaUsuario()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
            self::respuestaJSON(0, 'Acceso no autorizado. Debes iniciar sesión.', null, 401);
            return;
        }
        // Solo ADMIN puede eliminar usuarios
        if (!LoginController::tienePermiso(self::ROL_ADMIN)) {
            self::respuestaJSON(0, 'No tienes permiso para eliminar usuarios.', null, 403);
            return;
        }

        try {
            // Validar que llegue el ID
            if (empty($_POST['id_usuario'])) {
                self::respuestaJSON(0, 'ID de usuario requerido', null, 400);
            }

            $id =  $_POST['id_usuario'];

            //USAR HELPER: Obtener fotografía para eliminar
            $rutaFotografia = Usuarios::obtenerValor('fotografia', 'id_usuario', $id) ?? '';

            if (!empty($rutaFotografia)) {
                self::eliminarImagen($rutaFotografia);
            }

            // Eliminar lógicamente usando helper
            Usuarios::eliminarLogicoConRespuesta($id, 'id_usuario', '/elimina_usuario');
        } catch (Exception $e) {
            self::respuestaJSON(0, 'Error al eliminar usuario: ' . $e->getMessage(), null, 500);
        }
    }
}
