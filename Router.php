<?php

namespace MVC;

use Model\ActiveRecord;
use Exception;

class Router
{
    public $getRoutes = [];
    public $postRoutes = [];
    protected $base = '';

    public function get($url, $fn)
    {
        $this->getRoutes[$this->base . $url] = $fn;
    }

    public function post($url, $fn)
    {
        $this->postRoutes[$this->base . $url] = $fn;
    }

    public function setBaseURL($base)
    {
        $this->base = $base;
    }

    private function interpretarRuta($url, $method)
    {
        // Extraer módulo y acción
        if (preg_match('/\/([a-z_]+)_([a-z_]+)$/', $url, $matches)) {
            $accion = $matches[1]; // guarda, modifica, elimina, busca
            $modulo = $matches[2]; // usuario, aplicacion, tipo_arma

            $descripciones = [
                'guarda' => 'Crear',
                'modifica' => 'Modificar',
                'elimina' => 'Eliminar',
                'busca' => 'Consultar'
            ];

            $descripcion = $descripciones[$accion] . ' ' . str_replace('_', ' ', $modulo);
            return $descripcion;
        }

        return "Acción en " . $url;
    }
    private function registrarActividad($url, $idUsuario, $method)
    {
        try {
            // Solo registrar si hay usuario logueado y no es API o páginas estáticas
            if (!$idUsuario || strpos($url, '/api/') !== false || $method === 'GET') {
                return; // No registrar consultas GET ni APIs
            }

            // Interpretar la ruta para obtener descripción
            $descripcion = $this->interpretarRuta($url, $method);

            // Buscar o crear la ruta en dgcm_rutas
            $idRuta = $this->obtenerIdRuta($url, $descripcion);

            // Registrar en historial de actividades
            $queryHistorial = "INSERT INTO dgcm_historial_act 
                        (id_usuario, id_ruta, ejecucion, estado, situacion)
                        VALUES ($idUsuario, $idRuta, '$descripcion', 1, 1)";

            ActiveRecord::SQL($queryHistorial);
        } catch (Exception $e) {
            // Si hay error, no interrumpir el flujo normal
            error_log("Error registrando actividad: " . $e->getMessage());
        }
    }

    private function obtenerIdRuta($url, $descripcion)
    {
        // Buscar si la ruta ya existe
        $queryBuscar = "SELECT id_ruta FROM dgcm_rutas WHERE ruta = '$url'";
        $resultado = ActiveRecord::fetchFirst($queryBuscar);

        if ($resultado) {
            return $resultado['id_ruta'];
        }

        // MEJORADO: Determinar aplicación e descripción más descriptiva
        $idApp = $this->determinarAplicacion($url);
        $descripcionMejorada = $this->mejorarDescripcion($descripcion, $url);

        // Si no existe, crearla automáticamente con app y descripción mejorada
        $queryCrear = "INSERT INTO dgcm_rutas (id_app, ruta, descripcion, situacion)
                VALUES ($idApp, '$url', '$descripcionMejorada', 1)";
        ActiveRecord::SQL($queryCrear);

        // Obtener el ID de la ruta recién creada
        $resultado = ActiveRecord::fetchFirst($queryBuscar);
        return $resultado['id_ruta'];
    }

    private function determinarAplicacion($url)
    {
        // Por ahora, usar aplicación por defecto (puedes mejorarlo después)
        // Buscar la primera aplicación disponible
        $queryApp = "SELECT id_app FROM dgcm_aplicacion WHERE situacion = 1 LIMIT 1";
        $resultado = ActiveRecord::fetchFirst($queryApp);

        return $resultado ? $resultado['id_app'] : 1; // 1 como fallback
    }

    private function mejorarDescripcion($descripcion, $url)
    {
        // ==> Usuarios
        if (strpos($url, '/guarda_usuario') !== false) {
            return 'Se registró un usuario';
        }
        if (strpos($url, '/modifica_usuario') !== false) {
            return 'Se modificó un usuario';
        }
        if (strpos($url, '/elimina_usuario') !== false) {
            return 'Se eliminó un usuario';
        }

        // ==> Aplicaciones
        if (strpos($url, '/guarda_aplicacion') !== false) {
            return 'Se registró una aplicación';
        }
        if (strpos($url, '/modifica_aplicacion') !== false) {
            return 'Se modificó una aplicación';
        }
        if (strpos($url, '/elimina_aplicacion') !== false) {
            return 'Se eliminó una aplicación';
        }

        // ==> Permisos
        if (strpos($url, '/guarda_permiso') !== false) {
            return 'Se registró un permiso';
        }
        if (strpos($url, '/modifica_permiso') !== false) {
            return 'Se modificó un permiso';
        }
        if (strpos($url, '/elimina_permiso') !== false) {
            return 'Se eliminó un permiso';
        }

        // ==> Permiso de aplicación
        if (strpos($url, '/guarda_permiso_aplicacion') !== false) {
            return 'Se asignó un permiso a aplicación';
        }
        if (strpos($url, '/elimina_permiso_aplicacion') !== false) {
            return 'Se desasignó un permiso de aplicación';
        }

        // ==> Asignación de permisos
        if (strpos($url, '/guarda_asig_permiso') !== false) {
            return 'Se guardó una asignación de permiso';
        }
        if (strpos($url, '/modifica_asig_permiso') !== false) {
            return 'Se modificó una asignación de permiso';
        }
        if (strpos($url, '/elimina_asig_permiso') !== false) {
            return 'Se eliminó una asignación de permiso';
        }

        // ==> Rutas
        if (strpos($url, '/guarda_ruta') !== false) {
            return 'Se registró una ruta';
        }
        if (strpos($url, '/modifica_ruta') !== false) {
            return 'Se modificó una ruta';
        }
        if (strpos($url, '/elimina_ruta') !== false) {
            return 'Se eliminó una ruta';
        }

        // ==> Historial de rutas
        if (strpos($url, '/guarda_historial_ruta') !== false) {
            return 'Se guardó un historial de ruta';
        }
        if (strpos($url, '/modifica_historial_ruta') !== false) {
            return 'Se modificó un historial de ruta';
        }

        // ==> Armas
        if (strpos($url, '/guarda_arma') !== false) {
            return 'Se registró un arma';
        }
        if (strpos($url, '/modifica_arma') !== false) {
            return 'Se modificó un arma';
        }
        if (strpos($url, '/elimina_arma') !== false) {
            return 'Se eliminó un arma';
        }

        // ==> Tipos de arma
        if (strpos($url, '/guarda_tipo_arma') !== false) {
            return 'Se registró un tipo de arma';
        }
        if (strpos($url, '/modifica_tipo_arma') !== false) {
            return 'Se modificó un tipo de arma';
        }
        if (strpos($url, '/elimina_tipo_arma') !== false) {
            return 'Se eliminó un tipo de arma';
        }

        // Si no matchea ninguno de los anteriores, devolvemos la descripción original
        return $descripcion;
    }


    public function comprobarRutas()
    {

        session_start();

        $currentUrl = $_SERVER['REQUEST_URI'] ? str_replace("?" . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']) : $this->base . '/';
        $method = $_SERVER['REQUEST_METHOD'];
        // debuguear($currentUrl);
        if ($method === 'GET') {
            $fn = $this->getRoutes[$currentUrl] ?? null;
        } else {
            $fn = $this->postRoutes[$currentUrl] ?? null;
        }


        if ($fn) {


            if (session_status() == PHP_SESSION_ACTIVE && isset($_SESSION['id_usuario'])) {
                $queryActualizar = "UPDATE dgcm_contexto_sesion 
                                    SET ultima_actividad = CURRENT YEAR TO SECOND 
                                    WHERE id_usuario = {$_SESSION['id_usuario']}";
                ActiveRecord::SQL($queryActualizar);
            }

            // AGREGAR después de la línea 51:
            $this->registrarActividad($currentUrl, $_SESSION['id_usuario'], $method);

            // Call user fn va a llamar una función cuando no sabemos cual sera
            call_user_func($fn, $this); // This es para pasar argumentos
        } else {
            // debuguear($_SERVER);
            if (empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                $this->render('pages/notfound');
            } else {
                getHeadersApi();
                echo json_encode(["ERROR" => "PÁGINA NO ENCONTRADA"]);
            }
        }
    }

    public function render($view, $datos = [], $layout = 'layout')
    {

        // Leer lo que le pasamos  a la vista
        foreach ($datos as $key => $value) {
            $$key = $value;  // Doble signo de dolar significa: variable variable, básicamente nuestra variable sigue siendo la original, pero al asignarla a otra no la reescribe, mantiene su valor, de esta forma el nombre de la variable se asigna dinamicamente
        }

        ob_start(); // Almacenamiento en memoria durante un momento...

        // entonces incluimos la vista en el layout
        include_once __DIR__ . "/views/$view.php";
        $contenido = ob_get_clean(); // Limpia el Buffer
        include_once __DIR__ . "/views/layouts/$layout.php";
    }

    public function load($view, $datos = [])
    {
        foreach ($datos as $key => $value) {
            $$key = $value;  // Doble signo de dolar significa: variable variable, básicamente nuestra variable sigue siendo la original, pero al asignarla a otra no la reescribe, mantiene su valor, de esta forma el nombre de la variable se asigna dinamicamente
        }

        ob_start(); // Almacenamiento en memoria durante un momento...

        // entonces incluimos la vista en el layout
        include_once __DIR__ . "/views/$view.php";
        $contenido = ob_get_clean(); // Limpia el Buffer
        return $contenido;
    }

    public function printPDF($ruta)
    {

        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=filename.pdf");
        @readfile(__DIR__ . '/storage/' . $ruta);
    }
}
