<?php

namespace Model;

use Model\ActiveRecord;

class Permisos1 extends ActiveRecord {
    
    public static $tabla = 'permiso1';
    public static $idTabla = 'permiso_id';
    public static $columnasDB = 
    [
        'permiso_app_id',
        'permiso_nombre',
        'permiso_clave',
        'permiso_desc',
        'permiso_fecha',
        'permiso_situacion'
    ];
    
    public $permiso_id;
    public $permiso_app_id;
    public $permiso_nombre;
    public $permiso_clave;
    public $permiso_desc;
    public $permiso_fecha;
    public $permiso_situacion;
    
    public function __construct($permiso = [])
    {
        $this->permiso_id = $permiso['permiso_id'] ?? null;
        $this->permiso_app_id = $permiso['permiso_app_id'] ?? 0;
        $this->permiso_nombre = $permiso['permiso_nombre'] ?? '';
        $this->permiso_clave = $permiso['permiso_clave'] ?? '';
        $this->permiso_desc = $permiso['permiso_desc'] ?? '';
        $this->permiso_fecha = $permiso['permiso_fecha'] ?? date('Y-m-d H:i:s');
        $this->permiso_situacion = $permiso['permiso_situacion'] ?? 1;
    }

    public static function EliminarPermiso($id){
        $sql = "DELETE FROM permiso1 WHERE permiso_id = $id";
        return self::SQL($sql);
    }

    public static function ObtenerActivos(){
        $sql = "SELECT p.*, a.app_nombre_corto 
                FROM permiso1 p 
                INNER JOIN aplicacion a ON p.permiso_app_id = a.app_id 
                WHERE p.permiso_situacion = 1 
                ORDER BY p.permiso_fecha DESC";
        return self::SQL($sql);
    }

    public static function ObtenerPorAplicacion($app_id){
        $sql = "SELECT * FROM permiso1 WHERE permiso_app_id = $app_id AND permiso_situacion = 1";
        return self::SQL($sql);
    }

    public static function BuscarPorNombre($nombre){
        $sql = "SELECT p.*, a.app_nombre_corto 
                FROM permiso1 p 
                INNER JOIN aplicacion a ON p.permiso_app_id = a.app_id 
                WHERE p.permiso_nombre LIKE '%$nombre%' AND p.permiso_situacion = 1";
        return self::SQL($sql);
    }

    public static function BuscarPorClave($clave){
        $sql = "SELECT p.*, a.app_nombre_corto 
                FROM permiso1 p 
                INNER JOIN aplicacion a ON p.permiso_app_id = a.app_id 
                WHERE p.permiso_clave LIKE '%$clave%' AND p.permiso_situacion = 1";
        return self::SQL($sql);
    }

    public static function VerificarClaveUnica($clave, $app_id, $permiso_id = null){
        $where = "permiso_clave = '$clave' AND permiso_app_id = $app_id AND permiso_situacion = 1";
        if($permiso_id){
            $where .= " AND permiso_id != $permiso_id";
        }
        $sql = "SELECT COUNT(*) as total FROM permiso1 WHERE $where";
        $resultado = self::SQL($sql);
        $fila = $resultado->fetch_assoc();
        return $fila['total'] > 0;
    }
}