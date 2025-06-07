<?php

namespace Model;

class Usuario extends ActiveRecord {
    
    public static $tabla = 'usuario';
    
    // Solo incluir campos que SÍ se pueden insertar (excluir campos problemáticos)
    public static $columnasDB = [
        'usuario_nom1',
        'usuario_nom2',
        'usuario_ape1',
        'usuario_ape2',
        'usuario_tel',
        'usuario_direc',
        'usuario_dpi',
        'usuario_correo',
        // NO incluir: 'usuario_contra', 'usuario_token', 'usuario_fotografia'
        // porque tienen LVARCHAR que causa problemas
        'usuario_situacion'
    ];
    
    public static $idTabla = 'usuario_id';
    
    public $usuario_id;
    public $usuario_nom1;
    public $usuario_nom2;
    public $usuario_ape1;
    public $usuario_ape2;
    public $usuario_tel;
    public $usuario_direc;
    public $usuario_dpi;
    public $usuario_correo;
    public $usuario_situacion;
    
    public function __construct($args = []){
        $this->usuario_id = $args['usuario_id'] ?? null;
        $this->usuario_nom1 = $args['usuario_nom1'] ?? '';
        $this->usuario_nom2 = $args['usuario_nom2'] ?? '';
        $this->usuario_ape1 = $args['usuario_ape1'] ?? '';
        $this->usuario_ape2 = $args['usuario_ape2'] ?? '';
        $this->usuario_tel = $args['usuario_tel'] ?? 0;
        $this->usuario_direc = $args['usuario_direc'] ?? '';
        $this->usuario_dpi = $args['usuario_dpi'] ?? '';
        $this->usuario_correo = $args['usuario_correo'] ?? '';
        $this->usuario_situacion = $args['usuario_situacion'] ?? 1;
    }

    // Método personalizado para guardar con contraseña
    public function guardarConPassword($password, $token = '') {
        try {
            // Primero guardar los campos básicos
            $resultado = $this->guardar();
            
            if ($resultado && $this->usuario_id) {
                // Luego actualizar la contraseña con SQL directo
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $tokenHash = $token ?: md5(uniqid(rand(), true));
                
                $sql = "UPDATE usuario SET 
                        usuario_contra = ?, 
                        usuario_token = ? 
                        WHERE usuario_id = ?";
                
                $stmt = self::$db->prepare($sql);
                $resultado2 = $stmt->execute([$passwordHash, $tokenHash, $this->usuario_id]);
                
                return $resultado2;
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log('Error en guardarConPassword: ' . $e->getMessage());
            return false;
        }
    }
}