<?php

namespace Model;

class AsignacionPermisos extends ActiveRecord {

    public static $tabla = 'asig_permisos';
    public static $columnasDB = [
        'asignacion_usuario_id',
        'asignacion_app_id',
        'asignacion_permiso_id',
        'asignacion_fecha',
        'asignacion_usuario_asigno',
        'asignacion_motivo',
        'asignacion_situacion'
    ];

    public static $idTabla = 'asignacion_id';
    public $asignacion_id;
    public $asignacion_usuario_id;
    public $asignacion_app_id;
    public $asignacion_permiso_id;
    public $asignacion_fecha;
    public $asignacion_usuario_asigno;
    public $asignacion_motivo;
    public $asignacion_situacion;

    public function __construct($args = []){
        $this->asignacion_id = $args['asignacion_id'] ?? null;
        $this->asignacion_usuario_id = $args['asignacion_usuario_id'] ?? 0;
        $this->asignacion_app_id = $args['asignacion_app_id'] ?? 0;
        $this->asignacion_permiso_id = $args['asignacion_permiso_id'] ?? 0;
        $this->asignacion_fecha = $args['asignacion_fecha'] ?? date('Y-m-d H:i:s');
        $this->asignacion_usuario_asigno = $args['asignacion_usuario_asigno'] ?? 0;
        $this->asignacion_motivo = $args['asignacion_motivo'] ?? '';
        $this->asignacion_situacion = $args['asignacion_situacion'] ?? 1;
    }

    public static function EliminarAsignacion($id){
        $sql = "DELETE FROM asig_permisos WHERE asignacion_id = $id";
        return self::SQL($sql);
    }

    public static function ObtenerActivas(){
        $sql = "SELECT * FROM asig_permisos WHERE asignacion_situacion = 1 ORDER BY asignacion_fecha DESC";
        return self::SQL($sql);
    }

    public static function ObtenerPorUsuario($usuario_id){
        $sql = "SELECT * FROM asig_permisos WHERE asignacion_usuario_id = $usuario_id AND asignacion_situacion = 1";
        return self::SQL($sql);
    }

    public static function ObtenerPorUsuarioApp($usuario_id, $app_id){
        $sql = "SELECT * FROM asig_permisos WHERE asignacion_usuario_id = $usuario_id AND asignacion_app_id = $app_id AND asignacion_situacion = 1";
        return self::SQL($sql);
    }

    public static function VerificarPermiso($usuario_id, $app_id, $permiso_id){
        $sql = "SELECT COUNT(*) as total FROM asig_permisos WHERE asignacion_usuario_id = $usuario_id AND asignacion_app_id = $app_id AND asignacion_permiso_id = $permiso_id AND asignacion_situacion = 1";
        $resultado = self::SQL($sql);
        $fila = $resultado->fetch_assoc();
        return $fila['total'] > 0;
    }
}