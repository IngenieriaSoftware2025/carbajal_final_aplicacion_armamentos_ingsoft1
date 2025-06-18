<?php

namespace Controllers;

use MVC\Router;
use Model\ActiveRecord;

class MapaController extends ActiveRecord
{
    // Renderiza la vista del mapa
    public static function renderizarMapa(Router $router)
    {
        $router->render('mapas/index', []);
    }
}
