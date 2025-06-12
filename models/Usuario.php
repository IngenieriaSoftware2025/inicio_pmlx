<?php

namespace Model;

use Model\ActiveRecord;

class Usuario extends ActiveRecord
{
    protected static $tabla = 'usuario';
    protected static $idTabla = 'usuario_id';
    protected static $columnasDB = [
        'usuario_id',
        'usuario_nom1',
        'usuario_nom2', 
        'usuario_ape1',
        'usuario_ape2',
        'usuario_tel',
        'usuario_direc',
        'usuario_dpi',
        'usuario_correo',
        'usuario_contra',
        'usuario_token',
        'usuario_fecha_creacion',
        'usuario_fecha_contra',
        'usuario_fotografia',
        'usuario_situacion'
    ];

    public $usuario_id;
    public $usuario_nom1;
    public $usuario_nom2;
    public $usuario_ape1;
    public $usuario_ape2;
    public $usuario_tel;
    public $usuario_direc;
    public $usuario_dpi;
    public $usuario_correo;
    public $usuario_contra;
    public $usuario_token;
    public $usuario_fecha_creacion;
    public $usuario_fecha_contra;
    public $usuario_fotografia;
    public $usuario_situacion;

    public function __construct($args = [])
    {
        $this->usuario_id = $args['usuario_id'] ?? null;
        $this->usuario_nom1 = $args['usuario_nom1'] ?? '';
        $this->usuario_nom2 = $args['usuario_nom2'] ?? '';
        $this->usuario_ape1 = $args['usuario_ape1'] ?? '';
        $this->usuario_ape2 = $args['usuario_ape2'] ?? '';
        $this->usuario_tel = $args['usuario_tel'] ?? '';
        $this->usuario_direc = $args['usuario_direc'] ?? '';
        $this->usuario_dpi = $args['usuario_dpi'] ?? '';
        $this->usuario_correo = $args['usuario_correo'] ?? '';
        $this->usuario_contra = $args['usuario_contra'] ?? '';
        $this->usuario_token = $args['usuario_token'] ?? '';
        
        // IMPORTANTE: NO ESTABLECER FECHAS - Dejar que Informix use DEFAULT TODAY
        // Solo establecer si vienen en los args (para consultas)
        $this->usuario_fecha_creacion = $args['usuario_fecha_creacion'] ?? null;
        $this->usuario_fecha_contra = $args['usuario_fecha_contra'] ?? null;
        
        $this->usuario_fotografia = $args['usuario_fotografia'] ?? null;
        $this->usuario_situacion = $args['usuario_situacion'] ?? 1;
    }

    // Método para validar datos antes de guardar
    public function validar()
    {
        $errores = [];

        if (!$this->usuario_nom1) {
            $errores[] = 'El primer nombre es obligatorio';
        }

        if (!$this->usuario_nom2) {
            $errores[] = 'El segundo nombre es obligatorio';
        }

        if (!$this->usuario_ape1) {
            $errores[] = 'El primer apellido es obligatorio';
        }

        if (!$this->usuario_ape2) {
            $errores[] = 'El segundo apellido es obligatorio';
        }

        if (!$this->usuario_correo) {
            $errores[] = 'El correo es obligatorio';
        }

        if (!$this->usuario_dpi) {
            $errores[] = 'El DPI es obligatorio';
        }

        return $errores;
    }

    // Override del método crear para manejar campos DATE automáticos
    public function crear()
    {
        try {
            error_log("=== DATOS ANTES DE INSERTAR ===");
            error_log("Datos del objeto Usuario:");
            foreach (static::$columnasDB as $columna) {
                if ($columna !== 'usuario_id') { // No logear el ID
                    error_log("$columna: " . ($this->$columna ?? 'NULL'));
                }
            }
            
            // Para campos con DEFAULT, no los incluyas en el INSERT si son null
            $atributos = $this->sanitizarAtributos();
            
            // Eliminar fechas null para que Informix use DEFAULT TODAY
            if ($this->usuario_fecha_creacion === null) {
                unset($atributos['usuario_fecha_creacion']);
            }
            if ($this->usuario_fecha_contra === null) {
                unset($atributos['usuario_fecha_contra']);
            }
            
            error_log("Atributos a insertar: " . print_r($atributos, true));
            
            // Llamar al método padre con los atributos modificados
            $query = "INSERT INTO " . static::$tabla . " ( ";
            $query .= join(', ', array_keys($atributos));
            $query .= " ) VALUES ( ";
            $query .= join(', ', array_fill(0, count($atributos), '?'));
            $query .= " ) ";
            
            error_log("Query generado: " . $query);
            error_log("Valores: " . print_r(array_values($atributos), true));
            
            $db = self::getDB();
            $stmt = $db->prepare($query);
            $resultado = $stmt->execute(array_values($atributos));
            
            if ($resultado) {
                $this->usuario_id = $db->lastInsertId();
                return [
                    'resultado' => $resultado,
                    'id' => $this->usuario_id
                ];
            }
            
            return ['resultado' => 0];
            
        } catch (\Exception $e) {
            error_log("Error en Usuario::crear(): " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }
}