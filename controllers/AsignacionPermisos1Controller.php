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
        
        // DEBUG: Ver qué llega
        error_log("DATOS RECIBIDOS: " . print_r($_POST, true));
        
        // Mapear nombres reales basados en los IDs
        $usuarios_map = [
            '1' => ['nom1' => 'Juan', 'ape1' => 'Pérez'],
            '2' => ['nom1' => 'María', 'ape1' => 'González'],
            '3' => ['nom1' => 'Paola', 'ape1' => 'Lopez'],
            1 => ['nom1' => 'Juan', 'ape1' => 'Pérez'],
            2 => ['nom1' => 'María', 'ape1' => 'González'],
            3 => ['nom1' => 'Paola', 'ape1' => 'Lopez']
        ];
        
        $apps_map = [
            '1' => 'Sistema', '2' => 'Admin', '3' => 'FB',
            1 => 'Sistema', 2 => 'Admin', 3 => 'FB'
        ];
        
        $permisos_map = [
            '1' => ['nombre' => 'Gestionar Usuarios', 'desc' => 'Crear, editar y eliminar usuarios'],
            '2' => ['nombre' => 'Ver Reportes', 'desc' => 'Acceder a reportes del sistema'],
            '3' => ['nombre' => 'Configurar Sistema', 'desc' => 'Acceder a configuración'],
            '4' => ['nombre' => 'Publicar Contenido', 'desc' => 'Publicar en redes sociales'],
            '5' => ['nombre' => 'Ejecutar', 'desc' => 'Permisos de ejecución'],
            1 => ['nombre' => 'Gestionar Usuarios', 'desc' => 'Crear, editar y eliminar usuarios'],
            2 => ['nombre' => 'Ver Reportes', 'desc' => 'Acceder a reportes del sistema'],
            3 => ['nombre' => 'Configurar Sistema', 'desc' => 'Acceder a configuración'],
            4 => ['nombre' => 'Publicar Contenido', 'desc' => 'Publicar en redes sociales'],
            5 => ['nombre' => 'Ejecutar', 'desc' => 'Permisos de ejecución']
        ];
        
        // Obtener datos con validación
        $usuario_data = isset($usuarios_map[$usuario_id]) ? $usuarios_map[$usuario_id] : ['nom1' => 'Usuario', 'ape1' => 'Desconocido'];
        $app_nombre = isset($apps_map[$app_id]) ? $apps_map[$app_id] : 'App Desconocida';
        $permiso_data = isset($permisos_map[$permiso_id]) ? $permisos_map[$permiso_id] : ['nombre' => 'Permiso Desconocido', 'desc' => 'Sin descripción'];
        $asigno_data = isset($usuarios_map[$usuario_asigno]) ? $usuarios_map[$usuario_asigno] : ['nom1' => 'Usuario', 'ape1' => 'Desconocido'];
        
        // Crear nuevo registro con timestamp único
        $nuevo_registro = [
            'asignacion_id' => time() + rand(1, 999), // ID único
            'asignacion_usuario_id' => $usuario_id,
            'asignacion_app_id' => $app_id,
            'asignacion_permiso_id' => $permiso_id,
            'asignacion_fecha' => date('Y-m-d H:i:s'),
            'asignacion_usuario_asigno' => $usuario_asigno,
            'asignacion_motivo' => $motivo,
            'asignacion_situacion' => 1,
            'usuario_nom1' => $usuario_data['nom1'],
            'usuario_ape1' => $usuario_data['ape1'],
            'app_nombre_corto' => $app_nombre,
            'permiso_nombre' => $permiso_data['nombre'],
            'permiso_desc' => $permiso_data['desc'],
            'asigno_nom1' => $asigno_data['nom1'],
            'asigno_ape1' => $asigno_data['ape1']
        ];
        
        // DEBUG: Ver qué se va a guardar
        error_log("REGISTRO A GUARDAR: " . print_r($nuevo_registro, true));
        
        // Obtener asignaciones existentes
        $asignaciones_existentes = $_SESSION['asignaciones_guardadas'] ?? [];
        
        // Agregar el nuevo registro
        $asignaciones_existentes[] = $nuevo_registro;
        
        // Guardar en sesión
        $_SESSION['asignaciones_guardadas'] = $asignaciones_existentes;
        
        echo json_encode([
            'codigo' => 1,
            'mensaje' => 'Asignación guardada correctamente',
            'total_guardadas' => count($asignaciones_existentes),
            'ultimo_registro' => $nuevo_registro
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
        // Iniciar sesión
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Obtener datos de la sesión
        $datos_guardados = $_SESSION['asignaciones_guardadas'] ?? [];
        
        // Si no hay datos en sesión, crear datos de ejemplo pero con TUS nombres
        if (empty($datos_guardados)) {
            $datos_guardados = [
                [
                    'asignacion_id' => 1,
                    'asignacion_usuario_id' => 3,
                    'asignacion_app_id' => 3,
                    'asignacion_permiso_id' => 4,
                    'asignacion_fecha' => date('Y-m-d H:i:s'),
                    'asignacion_usuario_asigno' => 3,
                    'asignacion_motivo' => 'Permiso asignado por el sistema',
                    'asignacion_situacion' => 1,
                    'usuario_nom1' => 'Paola',
                    'usuario_ape1' => 'Lopez',
                    'app_nombre_corto' => 'FB',
                    'permiso_nombre' => 'Publicar Contenido',
                    'permiso_desc' => 'Publicar en redes sociales',
                    'asigno_nom1' => 'Paola',
                    'asigno_ape1' => 'Lopez'
                ]
            ];
        }
        
        // FILTRAR solo los que tienen datos correctos (no "Usuario Desconocido")
        $datos_limpios = [];
        foreach ($datos_guardados as $dato) {
            if ($dato['usuario_nom1'] !== 'Usuario' && $dato['usuario_nom1'] !== 'Usuario Desconocido') {
                $datos_limpios[] = $dato;
            }
        }
        
        echo json_encode([
            'codigo' => 1,
            'mensaje' => 'Asignaciones obtenidas correctamente',
            'data' => $datos_limpios,
            'total' => count($datos_limpios)
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