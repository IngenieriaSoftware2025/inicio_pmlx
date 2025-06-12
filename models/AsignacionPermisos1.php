<?php

namespace Model;

use Model\ActiveRecord;

class AsignacionPermisos1 extends ActiveRecord {

    public static $tabla = 'asig_permisos1';
    public static $columnasDB = [
        'asignacion_usuario_id',
        'asignacion_app_id',
        'asignacion_permiso_id',
        'asignacion_fecha',
        'asignacion_quitar_fechaPermiso',
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
    public $asignacion_quitar_fechaPermiso;
    public $asignacion_usuario_asigno;
    public $asignacion_motivo;
    public $asignacion_situacion;

    public function __construct($args = []){
        $this->asignacion_id = $args['asignacion_id'] ?? null;
        $this->asignacion_usuario_id = $args['asignacion_usuario_id'] ?? 0;
        $this->asignacion_app_id = $args['asignacion_app_id'] ?? 0;
        $this->asignacion_permiso_id = $args['asignacion_permiso_id'] ?? 0;
        $this->asignacion_fecha = $args['asignacion_fecha'] ?? date('Y-m-d H:i:s');
        $this->asignacion_quitar_fechaPermiso = $args['asignacion_quitar_fechaPermiso'] ?? null;
        $this->asignacion_usuario_asigno = $args['asignacion_usuario_asigno'] ?? 0;
        $this->asignacion_motivo = $args['asignacion_motivo'] ?? '';
        $this->asignacion_situacion = $args['asignacion_situacion'] ?? 1;
    }

    public static function EliminarAsignacion($id){
        $sql = "DELETE FROM asig_permisos1 WHERE asignacion_id = $id";
        return self::SQL($sql);
    }

    public static function ObtenerActivas(){
        $sql = "SELECT ap.*, 
                       u.usuario_nom1, u.usuario_ape1,
                       a.app_nombre_corto,
                       p.permiso_nombre, p.permiso_desc,
                       ua.usuario_nom1 as asigno_nom1, ua.usuario_ape1 as asigno_ape1
                FROM asig_permisos1 ap 
                INNER JOIN usuario u ON ap.asignacion_usuario_id = u.usuario_id
                INNER JOIN aplicacion a ON ap.asignacion_app_id = a.app_id 
                INNER JOIN permiso1 p ON ap.asignacion_permiso_id = p.permiso_id
                INNER JOIN usuario ua ON ap.asignacion_usuario_asigno = ua.usuario_id
                WHERE ap.asignacion_situacion = 1 
                ORDER BY ap.asignacion_fecha DESC";
        return self::SQL($sql);
    }

    public static function ObtenerPorUsuario($usuario_id){
        $sql = "SELECT ap.*, 
                       a.app_nombre_corto,
                       p.permiso_nombre, p.permiso_desc
                FROM asig_permisos1 ap 
                INNER JOIN aplicacion a ON ap.asignacion_app_id = a.app_id 
                INNER JOIN permiso1 p ON ap.asignacion_permiso_id = p.permiso_id
                WHERE ap.asignacion_usuario_id = $usuario_id AND ap.asignacion_situacion = 1
                ORDER BY a.app_nombre_corto, p.permiso_nombre";
        return self::SQL($sql);
    }

    public static function ObtenerPorUsuarioApp($usuario_id, $app_id){
        $sql = "SELECT ap.*, 
                       p.permiso_nombre, p.permiso_desc
                FROM asig_permisos1 ap 
                INNER JOIN permiso1 p ON ap.asignacion_permiso_id = p.permiso_id
                WHERE ap.asignacion_usuario_id = $usuario_id 
                AND ap.asignacion_app_id = $app_id 
                AND ap.asignacion_situacion = 1";
        return self::SQL($sql);
    }

    public static function VerificarPermiso($usuario_id, $app_id, $permiso_id){
        $sql = "SELECT COUNT(*) as total 
                FROM asig_permisos1 
                WHERE asignacion_usuario_id = $usuario_id 
                AND asignacion_app_id = $app_id 
                AND asignacion_permiso_id = $permiso_id 
                AND asignacion_situacion = 1";
        $resultado = self::SQL($sql);
        if($resultado){
            $fila = $resultado->fetch_assoc();
            return $fila['total'] > 0;
        }
        return false;
    }

    public static function ObtenerPermisosDisponibles($app_id){
        $sql = "SELECT permiso_id, permiso_nombre, permiso_desc 
                FROM permiso1 
                WHERE permiso_app_id = $app_id 
                AND permiso_situacion = 1 
                ORDER BY permiso_nombre";
        return self::SQL($sql);
    }

    public static function ObtenerEstadisticas(){
        $sql = "SELECT 
                    COUNT(*) as total_asignaciones,
                    COUNT(DISTINCT asignacion_usuario_id) as usuarios_con_permisos,
                    COUNT(DISTINCT asignacion_app_id) as apps_con_asignaciones
                FROM asig_permisos1 
                WHERE asignacion_situacion = 1";
        $resultado = self::SQL($sql);
        if($resultado){
            return $resultado->fetch_assoc();
        }
        return [];
    }
}