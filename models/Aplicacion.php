<?php

// crea nombre de espacio Model
namespace Model;

// Importa la clase ActiveRecord del nombre de espacio Model
use Model\ActiveRecord;

// Crea la clase de instancia Aplicacion y hereda las funciones de ActiveRecord
class Aplicacion extends ActiveRecord
{
    // Crea las propiedades de la clase
    public static $tabla = 'dgcm_aplicacion';
    public static $idTabla = ['id_app'];
    public static $columnasDB = [
        'nombre_app_lg',
        'nombre_app_md',
        'nombre_app_ct',
        'fecha_creacion',
        'situacion'
    ];

    // Crea las variables para almacenar los datos
    public $id_app;
    public $nombre_app_lg;
    public $nombre_app_md;
    public $nombre_app_ct;
    public $fecha_creacion;
    public $situacion;

    public function __construct($aplicacion = [])
    {
        $this->id_app = $aplicacion['id_app'] ?? null;
        $this->nombre_app_lg = $aplicacion['nombre_app_lg'] ?? '';
        $this->nombre_app_md = $aplicacion['nombre_app_md'] ?? '';
        $this->nombre_app_ct = $aplicacion['nombre_app_ct'] ?? '';
        $this->fecha_creacion = $aplicacion['fecha_creacion'] ?? null;
        $this->situacion = $aplicacion['situacion'] ?? 1;
    }
}
