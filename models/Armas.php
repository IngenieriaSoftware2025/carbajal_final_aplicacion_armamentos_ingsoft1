<?php

namespace Model;

use Model\ActiveRecord;

class Armas extends ActiveRecord
{
    protected static $tabla = 'dgcm_armas';
    protected static $idTabla = ['id_arma'];
    protected static $columnasDB = [
        'id_tipo_arma',
        'id_usuario',
        'cantidad',
        'estado',
        'fecha_registro',
        'situacion'
    ];

    public $id_arma;
    public $id_tipo_arma;
    public $id_usuario;
    public $cantidad;
    public $estado;
    public $fecha_registro;
    public $situacion;

    public function __construct($datos = [])
    {
        $this->id_arma = $datos['id_arma']  ?? null;
        $this->id_tipo_arma = $datos['id_tipo_arma'] ?? '';
        $this->id_usuario = $datos['id_usuario'] ?? '';
        $this->cantidad = $datos['cantidad']  ?? 0;
        $this->estado = $datos['estado'] ?? '';
        $this->fecha_registro  = $datos['fecha_registro'] ?? date('Y-m-d H:i:s');
        $this->situacion = $datos['situacion']  ?? 1;
    }
}
