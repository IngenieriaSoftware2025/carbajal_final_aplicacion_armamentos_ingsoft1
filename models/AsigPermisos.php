<?php

namespace Model;

use Model\ActiveRecord;

class AsigPermisos extends ActiveRecord
{
    public static $tabla = 'dgcm_asig_permisos';
    public static $idTabla = ['id_asig_permiso'];
    public static $columnasDB = [
        'id_usuario',
        'id_permiso_app',
        'fecha_creacion',
        'usuario_asigno',
        'fecha_expiro',
        'motivo',
        'situacion'
    ];

    public $id_asig_permiso;
    public $id_usuario;
    public $id_permiso_app;
    public $fecha_creacion;
    public $fecha_expiro;
    public $usuario_asigno;
    public $motivo;
    public $situacion;

    public function __construct($args = [])
    {
        $this->id_asig_permiso = $args['id_asig_permiso'] ?? null;
        $this->id_usuario = $args['id_usuario'] ?? '';
        $this->id_permiso_app = $args['id_permiso_app'] ?? '';
        $this->fecha_creacion = $args['fecha_creacion'] ?? null;
        $this->fecha_expiro = $args['fecha_expiro'] ?? null;
        $this->usuario_asigno = $args['usuario_asigno'] ?? '';
        $this->motivo = $args['motivo'] ?? '';
        $this->situacion = $args['situacion'] ?? 1;
    }
}
