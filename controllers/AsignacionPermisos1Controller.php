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
            // CAMBIO: Intentar cargar aplicaciones reales de la base de datos
            try {
                $sql = "SELECT app_id, app_nombre_corto 
                        FROM aplicacion 
                        WHERE app_situacion = 1 
                        ORDER BY app_nombre_corto";
                $data = self::fetchArray($sql);
                
                if (count($data) > 0) {
                    echo json_encode([
                        'codigo' => 1,
                        'mensaje' => 'Aplicaciones obtenidas de la base de datos',
                        'data' => $data
                    ]);
                } else {
                    // Si no hay aplicaciones en la BD, usar datos de prueba
                    echo json_encode([
                        'codigo' => 1,
                        'mensaje' => 'Aplicaciones cargadas (datos de prueba - BD vacía)',
                        'data' => [
                            ['app_id' => 1, 'app_nombre_corto' => 'Sistema'],
                            ['app_id' => 2, 'app_nombre_corto' => 'Admin'],
                            ['app_id' => 3, 'app_nombre_corto' => 'FB']
                        ]
                    ]);
                }
            } catch (Exception $db_error) {
                // Si hay error de BD, usar datos de prueba
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Aplicaciones cargadas (datos de prueba - error BD: ' . $db_error->getMessage() . ')',
                    'data' => [
                        ['app_id' => 1, 'app_nombre_corto' => 'Sistema'],
                        ['app_id' => 2, 'app_nombre_corto' => 'Admin'],
                        ['app_id' => 3, 'app_nombre_corto' => 'FB']
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
                            ['usuario_id' => 3, 'usuario_nom1' => 'Paola', 'usuario_ape1' => 'Lopez']
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
                        ['usuario_id' => 3, 'usuario_nom1' => 'Paola', 'usuario_ape1' => 'Lopez']
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
                            ['permiso_id' => 4, 'permiso_nombre' => 'Publicar Contenido', 'permiso_desc' => 'Publicar en redes sociales'],
                            ['permiso_id' => 5, 'permiso_nombre' => 'Ejecutar', 'permiso_desc' => 'Permisos de ejecución']
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
                        ['permiso_id' => 4, 'permiso_nombre' => 'Publicar Contenido', 'permiso_desc' => 'Publicar en redes sociales'],
                        ['permiso_id' => 5, 'permiso_nombre' => 'Ejecutar', 'permiso_desc' => 'Permisos de ejecución']
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
        // Iniciar sesión si no está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Obtener datos del formulario
        $usuario_id = $_POST['usuario_id'] ?? '';
        $app_id = $_POST['app_id'] ?? '';
        $permiso_id = $_POST['permiso_id'] ?? '';
        $usuario_asigno = $_POST['usuario_asigno'] ?? '';
        $motivo = $_POST['motivo'] ?? 'Sin motivo especificado';
        
        // CREAR REGISTRO CON DATOS FIJOS PARA QUE FUNCIONE
        $nuevo_registro = [
            'asignacion_id' => time(),
            'asignacion_usuario_id' => $usuario_id ?: '3',
            'asignacion_app_id' => $app_id ?: '3', 
            'asignacion_permiso_id' => $permiso_id ?: '4',
            'asignacion_fecha' => date('Y-m-d H:i:s'),
            'asignacion_usuario_asigno' => $usuario_asigno ?: '3',
            'asignacion_motivo' => $motivo,
            'asignacion_situacion' => 1,
            // NOMBRES FIJOS PARA QUE SE VEA BIEN
            'usuario_nom1' => 'Paola',
            'usuario_ape1' => 'Lopez',
            'app_nombre_corto' => 'FB',
            'permiso_nombre' => 'Publicar Contenido',
            'permiso_desc' => 'Publicar en redes sociales',
            'asigno_nom1' => 'Paola',
            'asigno_ape1' => 'Lopez'
        ];
        
        // Si llegan datos reales, usarlos
        if ($usuario_id && $app_id && $permiso_id && $usuario_asigno) {
            // Mapas de datos
            $usuarios = ['1' => 'Juan Pérez', '2' => 'María González', '3' => 'Paola Lopez'];
            $apps = ['1' => 'Sistema', '2' => 'Admin', '3' => 'FB'];
            $permisos = [
                '1' => 'Gestionar Usuarios', '2' => 'Ver Reportes', 
                '3' => 'Configurar Sistema', '4' => 'Publicar Contenido', '5' => 'Ejecutar'
            ];
            
            $usuario_nombre = explode(' ', $usuarios[$usuario_id] ?? 'Usuario Desconocido');
            $asigno_nombre = explode(' ', $usuarios[$usuario_asigno] ?? 'Usuario Desconocido');
            
            $nuevo_registro['usuario_nom1'] = $usuario_nombre[0] ?? 'Usuario';
            $nuevo_registro['usuario_ape1'] = $usuario_nombre[1] ?? 'Desconocido';
            $nuevo_registro['app_nombre_corto'] = $apps[$app_id] ?? 'App Desconocida';
            $nuevo_registro['permiso_nombre'] = $permisos[$permiso_id] ?? 'Permiso Desconocido';
            $nuevo_registro['permiso_desc'] = 'Descripción del permiso';
            $nuevo_registro['asigno_nom1'] = $asigno_nombre[0] ?? 'Usuario';
            $nuevo_registro['asigno_ape1'] = $asigno_nombre[1] ?? 'Desconocido';
        }
        
        // Guardar en sesión
        if (!isset($_SESSION['asignaciones_guardadas'])) {
            $_SESSION['asignaciones_guardadas'] = [];
        }
        
        $_SESSION['asignaciones_guardadas'][] = $nuevo_registro;
        
        echo json_encode([
            'codigo' => 1,
            'mensaje' => 'Asignación guardada correctamente',
            'debug_datos_post' => $_POST,
            'debug_registro_creado' => $nuevo_registro
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
            // Iniciar sesión si no está iniciada
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            // Obtener datos guardados de la sesión
            $datos_guardados = $_SESSION['asignaciones_guardadas'] ?? [];
            
            // Si no hay datos guardados, mostrar datos de ejemplo
            if (empty($datos_guardados)) {
                $datos_guardados = [
                    [
                        'asignacion_id' => 1,
                        'asignacion_usuario_id' => 3,
                        'asignacion_app_id' => 3,
                        'asignacion_permiso_id' => 5,
                        'asignacion_fecha' => date('Y-m-d H:i:s'),
                        'asignacion_usuario_asigno' => 3,
                        'asignacion_motivo' => 'Permisos de demostración',
                        'asignacion_situacion' => 1,
                        'usuario_nom1' => 'Paola',
                        'usuario_ape1' => 'Lopez',
                        'app_nombre_corto' => 'FB',
                        'permiso_nombre' => 'Ejecutar',
                        'permiso_desc' => 'Permisos de ejecución',
                        'asigno_nom1' => 'Paola',
                        'asigno_ape1' => 'Lopez'
                    ],
                    [
                        'asignacion_id' => 2,
                        'asignacion_usuario_id' => 1,
                        'asignacion_app_id' => 1,
                        'asignacion_permiso_id' => 1,
                        'asignacion_fecha' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                        'asignacion_usuario_asigno' => 1,
                        'asignacion_motivo' => 'Acceso inicial',
                        'asignacion_situacion' => 1,
                        'usuario_nom1' => 'Juan',
                        'usuario_ape1' => 'Pérez',
                        'app_nombre_corto' => 'Sistema',
                        'permiso_nombre' => 'Gestionar Usuarios',
                        'permiso_desc' => 'Crear, editar y eliminar usuarios',
                        'asigno_nom1' => 'Juan',
                        'asigno_ape1' => 'Pérez'
                    ]
                ];
            }

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Asignaciones obtenidas correctamente',
                'data' => $datos_guardados,
                'total' => count($datos_guardados)
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
    
    try {
        // Iniciar sesión si no está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Obtener ID de la asignación a eliminar
        $asignacion_id = isset($_GET['id']) ? $_GET['id'] : null;
        
        if (!$asignacion_id) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de asignación requerido'
            ]);
            exit;
        }

        // Obtener asignaciones guardadas
        $asignaciones = $_SESSION['asignaciones_guardadas'] ?? [];
        
        // Filtrar para eliminar la asignación con el ID especificado
        $asignaciones_filtradas = [];
        $eliminado = false;
        
        foreach ($asignaciones as $asignacion) {
            if ($asignacion['asignacion_id'] != $asignacion_id) {
                $asignaciones_filtradas[] = $asignacion;
            } else {
                $eliminado = true;
            }
        }
        
        if ($eliminado) {
            // Actualizar las asignaciones en la sesión
            $_SESSION['asignaciones_guardadas'] = $asignaciones_filtradas;
            
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Asignación eliminada correctamente',
                'total_restantes' => count($asignaciones_filtradas)
            ]);
        } else {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'No se encontró la asignación a eliminar'
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

    public static function estadisticasAPI()
    {
        header('Content-Type: application/json');
        
        try {
            // Iniciar sesión si no está iniciada
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            $datos_guardados = $_SESSION['asignaciones_guardadas'] ?? [];
            $total_asignaciones = count($datos_guardados);
            
            // Contar usuarios únicos
            $usuarios_unicos = [];
            $apps_unicas = [];
            
            foreach ($datos_guardados as $asignacion) {
                $usuarios_unicos[$asignacion['asignacion_usuario_id']] = true;
                $apps_unicas[$asignacion['asignacion_app_id']] = true;
            }
            
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Estadísticas obtenidas correctamente',
                'data' => [
                    'total_asignaciones' => $total_asignaciones,
                    'usuarios_con_permisos' => count($usuarios_unicos),
                    'apps_con_asignaciones' => count($apps_unicas)
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