<?php

namespace Model;

class AsignacionRoles extends ActiveRecord {

    public static $tabla = 'asig_permisos';
    public static $columnasDB = [
        'roles_usuario_id',
        'roles_app_id',
        'roles_permiso_id',
        'roles_fecha',
        'roles_usuario_asigno',
        'roles_motivo',
        'roles_situacion'
    ];

    public static $idTabla = 'asignacion_id';
    public $roles_id;
    public $roles_usuario_id;
    public $roles_app_id;
    public $roles_permiso_id;
    public $roles_fecha;
    public $roles_usuario_asigno;
    public $roles_motivo;
    public $roles_situacion;

    public function __construct($args = []){
        $this->roles_id = $args['roles_id'] ?? null;
        $this->roles_usuario_id = $args['roles_usuario_id'] ?? 0;
        $this->roles_app_id = $args['roles_app_id'] ?? 0;
        $this->roles_permiso_id = $args['roles_permiso_id'] ?? 0;
        $this->roles_fecha = $args['roles_fecha'] ?? date('Y-m-d H:i:s');
        $this->roles_usuario_asigno = $args['roles_usuario_asigno'] ?? 0;
        $this->roles_motivo = $args['roles_motivo'] ?? '';
        $this->roles_situacion = $args['roles_situacion'] ?? 1;
    }

    public static function EliminarPermiso($id){
        $sql = "DELETE FROM asig_roles WHERE roles_id = $id";
        return self::SQL($sql);
    }

    public static function ObtenerActivas(){
        $sql = "SELECT * FROM roles_permisos WHERE roles_situacion = 1 ORDER BY roles_fecha DESC";
        return self::SQL($sql);
    }

    public static function ObtenerPorUsuario($usuario_id){
        $sql = "SELECT * FROM roles_permisos WHERE roles_usuario_id = $usuario_id AND roles_situacion = 1";
        return self::SQL($sql);
    }

    public static function ObtenerPorUsuarioApp($usuario_id, $app_id){
        $sql = "SELECT * FROM asig_roles WHERE roles_usuario_id = $usuario_id AND roles_app_id = $app_id AND roles_situacion = 1";
        return self::SQL($sql);
    }

    public static function VerificarRoles($usuario_id, $app_id, $permiso_id){
        $sql = "SELECT COUNT(*) as total FROM roles_permisos WHERE roles_usuario_id = $usuario_id AND roles_app_id = $app_id AND roles_permiso_id = $permiso_id AND asignacion_situacion = 1";
        $resultado = self::SQL($sql);
        $fila = $resultado->fetch_assoc();
        return $fila['total'] > 0;
    }
}