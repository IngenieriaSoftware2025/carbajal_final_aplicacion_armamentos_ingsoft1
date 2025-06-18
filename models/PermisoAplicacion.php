<?php

namespace Model;

use Model\ActiveRecord;

class PermisoAplicacion extends ActiveRecord
{
    public static $tabla = 'dgcm_permiso_aplicacion';
    public static $idTabla = ['id_permiso_app'];
    public static $columnasDB = [
        'id_permiso',
        'id_app',
        'fecha_creacion',
        'situacion'
    ];

    public $id_permiso_app;
    public $id_permiso;
    public $id_app;
    public $fecha_creacion;
    public $situacion;

    public function __construct($args = [])
    {
        $this->id_permiso_app = $args['id_permiso_app'] ?? null;
        $this->id_permiso = $args['id_permiso'] ?? '';
        $this->id_app = $args['id_app'] ?? '';
        $this->fecha_creacion = $args['fecha_creacion'] ?? date('Y-m-d H:i:s');
        $this->situacion = $args['situacion'] ?? 1;
    }
}
