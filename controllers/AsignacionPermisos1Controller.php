<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;

class AsignacionPermisos1Controller extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('asignacion_permisos1/index', []);
    }

    public static function buscarAplicacionesAPI()
    {
        header('Content-Type: application/json');
        
        try {
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Aplicaciones cargadas correctamente',
                'data' => [
                    ['app_id' => 1, 'app_nombre_corto' => 'Sistema'],
                    ['app_id' => 2, 'app_nombre_corto' => 'Admin'],
                    ['app_id' => 3, 'app_nombre_corto' => 'FB']
                ]
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public static function buscarUsuariosAPI()
    {
        header('Content-Type: application/json');
        
        try {
            // CAMBIO: Intentar cargar usuarios reales de la base de datos
            try {
                $sql = "SELECT usuario_id, usuario_nom1, usuario_ape1 
                        FROM usuario 
                        WHERE usuario_situacion = 1 
                        ORDER BY usuario_nom1";
                $data = self::fetchArray($sql);
                
                if (count($data) > 0) {
                    echo json_encode([
                        'codigo' => 1,
                        'mensaje' => 'Usuarios obtenidos de la base de datos',
                        'data' => $data
                    ]);
                } else {
                    // Si no hay usuarios en la BD, usar datos de prueba
                    echo json_encode([
                        'codigo' => 1,
                        'mensaje' => 'Usuarios cargados (datos de prueba - BD vacía)',
                        'data' => [
                            ['usuario_id' => 1, 'usuario_nom1' => 'Juan', 'usuario_ape1' => 'Pérez'],
                            ['usuario_id' => 2, 'usuario_nom1' => 'María', 'usuario_ape1' => 'González'],
                            ['usuario_id' => 3, 'usuario_nom1' => 'Carlos', 'usuario_ape1' => 'López']
                        ]
                    ]);
                }
            } catch (Exception $db_error) {
                // Si hay error de BD, usar datos de prueba
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Usuarios cargados (datos de prueba - error BD: ' . $db_error->getMessage() . ')',
                    'data' => [
                        ['usuario_id' => 1, 'usuario_nom1' => 'Juan', 'usuario_ape1' => 'Pérez'],
                        ['usuario_id' => 2, 'usuario_nom1' => 'María', 'usuario_ape1' => 'González'],
                        ['usuario_id' => 3, 'usuario_nom1' => 'Carlos', 'usuario_ape1' => 'López']
                    ]
                ]);
            }

        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public static function buscarPermisosAPI()
    {
        header('Content-Type: application/json');
        
        try {
            $app_id = isset($_GET['app_id']) ? filter_var($_GET['app_id'], FILTER_SANITIZE_NUMBER_INT) : null;
            
            if (!$app_id || $app_id <= 0) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de aplicación requerido',
                    'data' => []
                ]);
                exit;
            }

            // CAMBIO: Intentar cargar permisos reales de la base de datos
            try {
                $sql = "SELECT permiso_id, permiso_nombre, permiso_desc 
                        FROM permiso1 
                        WHERE permiso_app_id = $app_id AND permiso_situacion = 1 
                        ORDER BY permiso_nombre";
                $data = self::fetchArray($sql);
                
                if (count($data) > 0) {
                    echo json_encode([
                        'codigo' => 1,
                        'mensaje' => 'Permisos obtenidos de la base de datos',
                        'data' => $data
                    ]);
                } else {
                    // Si no hay permisos para esa app, usar datos de prueba
                    $permisos = [
                        1 => [
                            ['permiso_id' => 1, 'permiso_nombre' => 'Gestionar Usuarios', 'permiso_desc' => 'Crear, editar y eliminar usuarios'],
                            ['permiso_id' => 2, 'permiso_nombre' => 'Ver Reportes', 'permiso_desc' => 'Acceder a reportes del sistema']
                        ],
                        2 => [
                            ['permiso_id' => 3, 'permiso_nombre' => 'Configurar Sistema', 'permiso_desc' => 'Acceder a configuración']
                        ],
                        3 => [
                            ['permiso_id' => 4, 'permiso_nombre' => 'Publicar Contenido', 'permiso_desc' => 'Publicar en redes sociales']
                        ]
                    ];

                    $data = isset($permisos[$app_id]) ? $permisos[$app_id] : [];

                    echo json_encode([
                        'codigo' => 1,
                        'mensaje' => 'Permisos cargados (datos de prueba - BD vacía para app ' . $app_id . ')',
                        'data' => $data
                    ]);
                }
            } catch (Exception $db_error) {
                // Si hay error de BD, usar datos de prueba
                $permisos = [
                    1 => [
                        ['permiso_id' => 1, 'permiso_nombre' => 'Gestionar Usuarios', 'permiso_desc' => 'Crear, editar y eliminar usuarios'],
                        ['permiso_id' => 2, 'permiso_nombre' => 'Ver Reportes', 'permiso_desc' => 'Acceder a reportes del sistema']
                    ],
                    2 => [
                        ['permiso_id' => 3, 'permiso_nombre' => 'Configurar Sistema', 'permiso_desc' => 'Acceder a configuración']
                    ],
                    3 => [
                        ['permiso_id' => 4, 'permiso_nombre' => 'Publicar Contenido', 'permiso_desc' => 'Publicar en redes sociales']
                    ]
                ];

                $data = isset($permisos[$app_id]) ? $permisos[$app_id] : [];

                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Permisos cargados (datos de prueba - error BD: ' . $db_error->getMessage() . ')',
                    'data' => $data
                ]);
            }

        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public static function guardarAPI()
    {
        header('Content-Type: application/json');
        
        try {
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Asignación guardada correctamente (modo prueba)',
                'datos' => $_POST
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public static function buscarAPI()
    {
        header('Content-Type: application/json');
        
        try {
            // Datos de prueba para mostrar en la tabla
            $datos_prueba = [
                [
                    'asignacion_id' => 1,
                    'asignacion_usuario_id' => 1,
                    'asignacion_app_id' => 1,
                    'asignacion_permiso_id' => 1,
                    'asignacion_fecha' => date('Y-m-d H:i:s'),
                    'asignacion_usuario_asigno' => 1,
                    'asignacion_motivo' => 'Asignación inicial',
                    'asignacion_situacion' => 1,
                    'usuario_nom1' => 'Juan',
                    'usuario_ape1' => 'Pérez',
                    'app_nombre_corto' => 'Sistema',
                    'permiso_nombre' => 'Gestionar Usuarios',
                    'permiso_desc' => 'Crear, editar y eliminar usuarios',
                    'asigno_nom1' => 'Admin',
                    'asigno_ape1' => 'Sistema'
                ],
                [
                    'asignacion_id' => 2,
                    'asignacion_usuario_id' => 2,
                    'asignacion_app_id' => 1,
                    'asignacion_permiso_id' => 2,
                    'asignacion_fecha' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                    'asignacion_usuario_asigno' => 1,
                    'asignacion_motivo' => 'Acceso a reportes',
                    'asignacion_situacion' => 1,
                    'usuario_nom1' => 'María',
                    'usuario_ape1' => 'González',
                    'app_nombre_corto' => 'Sistema',
                    'permiso_nombre' => 'Ver Reportes',
                    'permiso_desc' => 'Acceder a reportes del sistema',
                    'asigno_nom1' => 'Admin',
                    'asigno_ape1' => 'Sistema'
                ],
                [
                    'asignacion_id' => 3,
                    'asignacion_usuario_id' => 3,
                    'asignacion_app_id' => 2,
                    'asignacion_permiso_id' => 3,
                    'asignacion_fecha' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                    'asignacion_usuario_asigno' => 1,
                    'asignacion_motivo' => 'Configuración del sistema',
                    'asignacion_situacion' => 1,
                    'usuario_nom1' => 'Carlos',
                    'usuario_ape1' => 'López',
                    'app_nombre_corto' => 'Admin',
                    'permiso_nombre' => 'Configurar Sistema',
                    'permiso_desc' => 'Acceder a configuración',
                    'asigno_nom1' => 'Admin',
                    'asigno_ape1' => 'Sistema'
                ]
            ];

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Asignaciones obtenidas correctamente',
                'data' => $datos_prueba,
                'total' => count($datos_prueba)
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public static function modificarAPI()
    {
        header('Content-Type: application/json');
        echo json_encode(['codigo' => 1, 'mensaje' => 'Modificación exitosa (prueba)']);
        exit;
    }

    public static function EliminarAPI()
    {
        header('Content-Type: application/json');
        echo json_encode(['codigo' => 1, 'mensaje' => 'Eliminación exitosa (prueba)']);
        exit;
    }

    public static function estadisticasAPI()
    {
        header('Content-Type: application/json');
        
        try {
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Estadísticas obtenidas correctamente',
                'data' => [
                    'total_asignaciones' => 5,
                    'usuarios_con_permisos' => 3,
                    'apps_con_asignaciones' => 2
                ]
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public static function verificarPermisoAPI()
    {
        header('Content-Type: application/json');
        
        try {
            $usuario_id = isset($_GET['usuario_id']) ? $_GET['usuario_id'] : 0;
            $app_id = isset($_GET['app_id']) ? $_GET['app_id'] : 0;
            $permiso_id = isset($_GET['permiso_id']) ? $_GET['permiso_id'] : 0;

            // Simulación: devuelve false para evitar duplicados en el modo prueba
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Permiso verificado',
                'tiene_permiso' => false
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public static function obtenerPorUsuarioAPI()
    {
        header('Content-Type: application/json');
        
        try {
            $usuario_id = isset($_GET['usuario_id']) ? $_GET['usuario_id'] : 0;

            // Datos de prueba basados en el usuario
            $permisos_usuario = [];
            if ($usuario_id == 1) {
                $permisos_usuario = [
                    [
                        'asignacion_id' => 1,
                        'app_nombre_corto' => 'Sistema',
                        'permiso_nombre' => 'Gestionar Usuarios',
                        'permiso_desc' => 'Crear, editar y eliminar usuarios'
                    ]
                ];
            }

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Permisos del usuario obtenidos',
                'data' => $permisos_usuario
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error: ' . $e->getMessage()
            ]);
        }
        exit;
    }
}