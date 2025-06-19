<?php

// crea nombre de espacio Model
namespace Model;

// Importa la clase ActiveRecord del nombre de espacio Model
use Model\ActiveRecord;

// Crea la clase de instancia HistorialAct y hereda las funciones de ActiveRecord
class HistorialAct extends ActiveRecord
{
    // Crea las propiedades de la clase
    public static $tabla = 'dgcm_historial_act';
    public static $idTabla = ['id_hist_act'];
    public static $columnasDB = [
        'id_usuario',
        'id_ruta',
        'fecha_creacion',
        'ejecucion',
        'estado',
        'situacion'
    ];

    // Crea las variables para almacenar los datos
    public $id_hist_act;
    public $id_usuario;
    public $id_ruta;
    public $fecha_creacion;
    public $ejecucion;
    public $estado;
    public $situacion;

    public function __construct($historial = [])
    {
        $this->id_hist_act = $historial['id_hist_act'] ?? null;
        $this->id_usuario = $historial['id_usuario'] ?? null;
        $this->id_ruta = $historial['id_ruta'] ?? null;
        $this->fecha_creacion = $historial['fecha_creacion'] ?? null;
        $this->ejecucion = $historial['ejecucion'] ?? '';
        $this->estado = $historial['estado'] ?? null;
        $this->situacion = $historial['situacion'] ?? 1;
    }
}
