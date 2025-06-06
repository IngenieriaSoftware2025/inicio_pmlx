<?php

namespace Model;

class Sesion extends ActiveRecord {
    
    public static $tabla = 'sesion';
    
    public static $columnasDB = [
        'usuario',
        'password'
    ];
    
    public static $idTabla = 'login_id';
    
    public $login_id;
    public $usuario;
    public $password;
    
    public function __construct($args = []){
        $this->login_id = $args['login_id'] ?? null;
        $this->usuario = $args['usuario'] ?? '';
        $this->password = $args['password'] ?? '';
    }
    
}