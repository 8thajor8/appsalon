<?php

namespace Model;

class Servicio extends ActiveRecord{

    //Base de datos
    protected static $tabla = 'servicios';
    protected static $columnasDB = ['id','nombre', 'precio'];

    public $id;
    public $nombre;
    public $precio;

    public function __construct ($args = []){

        $this->id = $args['id'] ?? NULL;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';

    }

    public function validar(){

        if(!$this->nombre){
            self::$alertas['error'][] = "El nombre es obligatorio"; 
        }

        if(!$this->precio){
            self::$alertas['error'][] = "El precio es obligatorio"; 
        }

        return self::$alertas;
    }

}