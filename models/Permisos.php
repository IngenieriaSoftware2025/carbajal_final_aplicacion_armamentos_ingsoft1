<?php

// crea nombre de espacio Model
namespace Model;

// Importa la clase ActiveRecord del nombre de espacio Model
use Model\ActiveRecord;

// Crea la clase de instancia Permisos y hereda las funciones de ActiveRecord
class Permisos extends ActiveRecord
{
    // Crea las propiedades de la clase
    public static $tabla = 'dgcm_permisos';
    public static $idTabla = ['id_permiso'];
    public static $columnasDB = [
        'nombre_permiso',
        'clave_permiso',
        'descripcion',
        'fecha_creacion',
        'situacion'
    ];

    // Crea las variables para almacenar los datos
    public $id_permiso;
    public $nombre_permiso;
    public $clave_permiso;
    public $descripcion;
    public $fecha_creacion;
    public $situacion;

    public function __construct($permiso = [])
    {
        $this->id_permiso = $permiso['id_permiso'] ?? null;
        $this->nombre_permiso = $permiso['nombre_permiso'] ?? '';
        $this->clave_permiso = $permiso['clave_permiso'] ?? '';
        $this->descripcion = $permiso['descripcion'] ?? '';
        $this->fecha_creacion = $permiso['fecha_creacion'] ?? null;
        $this->situacion = $permiso['situacion'] ?? 1;
    }
}
