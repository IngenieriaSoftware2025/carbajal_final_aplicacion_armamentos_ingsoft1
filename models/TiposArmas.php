<?php

namespace Model;

use Model\ActiveRecord;

class TiposArmas extends ActiveRecord
{
    protected static $tabla = 'dgcm_tipos_armas';
    protected static $idTabla = ['id_tipo_arma'];
    protected static $columnasDB = [
        'nombre_tipo',
        'calibre',
        'fecha_creacion',
        'situacion'
    ];

    public $id_tipo_arma;
    public $nombre_tipo;
    public $calibre;
    public $fecha_creacion;
    public $situacion;

    public function __construct($args = [])
    {
        $this->id_tipo_arma = $args['id_tipo_arma'] ?? null;
        $this->nombre_tipo = $args['nombre_tipo'] ?? '';
        $this->calibre = $args['calibre'] ?? '';
        $this->fecha_creacion = $args['fecha_creacion'] ?? date('Y-m-d H:i:s');
        $this->situacion = $args['situacion'] ?? 'activo';
    }
}
