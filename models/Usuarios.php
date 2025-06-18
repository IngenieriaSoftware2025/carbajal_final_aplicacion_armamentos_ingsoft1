<?php

// crea nombre de espacio Model
namespace Model;

// Importa la clase ActiveRecord del nombre de espacio Model
use Model\ActiveRecord;

// Crea la clase de instancia Usuarios y hereda las funciones de ActiveRecord
class Usuarios extends ActiveRecord
{
    // Crea las propiedades de la clase
    public static $tabla = 'dgcm_usuarios';
    public static $idTabla = ['id_usuario'];
    public static $columnasDB = [
        'nombre1',
        'nombre2',
        'apellido1',
        'apellido2',
        'telefono',
        'dpi',
        'correo',
        'usuario_clave',
        'token',
        'fecha_creacion',
        'fecha_clave',
        'fotografia',
        'situacion'
    ];

    // Crea las variables para almacenar los datos
    public $id_usuario;
    public $nombre1;
    public $nombre2;
    public $apellido1;
    public $apellido2;
    public $telefono;
    public $dpi;
    public $correo;
    public $usuario_clave;
    public $token;
    public $fecha_creacion;
    public $fecha_clave;
    public $fotografia;
    public $situacion;

    public function __construct($usuario = [])
    {
        $this->id_usuario = $usuario['id_usuario'] ?? null;
        $this->nombre1 = $usuario['nombre1'] ?? '';
        $this->nombre2 = $usuario['nombre2'] ?? '';
        $this->apellido1 = $usuario['apellido1'] ?? '';
        $this->apellido2 = $usuario['apellido2'] ?? '';
        $this->telefono = $usuario['telefono'] ?? '';
        $this->dpi = $usuario['dpi'] ?? '';
        $this->correo = $usuario['correo'] ?? '';
        $this->usuario_clave = $usuario['usuario_clave'] ?? '';
        $this->token = $usuario['token'] ?? '';
        $this->fecha_creacion = $usuario['fecha_creacion'] ?? null;
        $this->fecha_clave = $usuario['fecha_clave'] ?? null;
        $this->fotografia = $usuario['fotografia'] ?? '';
        $this->situacion = $usuario['situacion'] ?? 1;
    }

    public function atributos()
    {
        $atributos = [];
        foreach (static::$columnasDB as $columna) {
            $columna = strtolower($columna);

            // Solo agregar si la propiedad existe y no es null
            if (property_exists($this, $columna)) {
                $atributos[$columna] = $this->$columna;
            }
        }
        return $atributos;
    }
}
