<?php 

namespace Controllers;

use Exception;
use Model\Usuario;
use MVC\Router;

class UsuariosController {

    public static function mostrarPagina(Router $router){
        $router->render('usuarios/index', [], 'layout/layout');
    }

    public static function obtenerUsuariosAPI()
    {
        // Configurar headers para API
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Content-Type');
        
        try {
            // Verificar que sea GET
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                http_response_code(405);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Método no permitido'
                ]);
                return;
            }

            error_log("=== OBTENIENDO USUARIOS ===");

            // Usar ActiveRecord para obtener usuarios (más compatible)
            $usuarios = Usuario::all();
            
            error_log("Usuarios encontrados: " . count($usuarios));
            
            // Procesar usuarios para incluir información de fotografía
            $usuariosProcesados = [];
            
            foreach ($usuarios as $usuario) {
                // Convertir objeto a array
                $usuarioArray = [];
                
                // Mapear todas las propiedades manualmente para evitar undefined
                $usuarioArray['usuario_id'] = $usuario->usuario_id ?? '';
                $usuarioArray['usuario_nom1'] = $usuario->usuario_nom1 ?? '';
                $usuarioArray['usuario_nom2'] = $usuario->usuario_nom2 ?? '';
                $usuarioArray['usuario_ape1'] = $usuario->usuario_ape1 ?? '';
                $usuarioArray['usuario_ape2'] = $usuario->usuario_ape2 ?? '';
                $usuarioArray['usuario_tel'] = $usuario->usuario_tel ?? '';
                $usuarioArray['usuario_direc'] = $usuario->usuario_direc ?? '';
                $usuarioArray['usuario_dpi'] = $usuario->usuario_dpi ?? '';
                $usuarioArray['usuario_correo'] = $usuario->usuario_correo ?? '';
                $usuarioArray['usuario_situacion'] = $usuario->usuario_situacion ?? 1;
                
                // Manejar fechas de Informix - convertir a formato legible
                if (isset($usuario->usuario_fecha_creacion) && $usuario->usuario_fecha_creacion) {
                    // Informix puede devolver fechas en diferentes formatos, intentamos convertir
                    try {
                        $fecha = $usuario->usuario_fecha_creacion;
                        // Si es un objeto DateTime
                        if ($fecha instanceof \DateTime) {
                            $usuarioArray['usuario_fecha_creacion'] = $fecha->format('Y-m-d');
                        } else {
                            // Si es string, intentar convertir
                            $timestamp = strtotime($fecha);
                            if ($timestamp !== false) {
                                $usuarioArray['usuario_fecha_creacion'] = date('Y-m-d', $timestamp);
                            } else {
                                $usuarioArray['usuario_fecha_creacion'] = date('Y-m-d'); // Fecha actual como fallback
                            }
                        }
                    } catch (Exception $e) {
                        $usuarioArray['usuario_fecha_creacion'] = date('Y-m-d'); // Fecha actual como fallback
                    }
                } else {
                    $usuarioArray['usuario_fecha_creacion'] = date('Y-m-d');
                }
                
                // Manejar fotografía
                if ($usuario->usuario_fotografia && file_exists(__DIR__ . "/../../" . $usuario->usuario_fotografia)) {
                    $usuarioArray['tiene_foto'] = true;
                    
                    // Leer archivo y convertir a base64
                    $imagenPath = __DIR__ . "/../../" . $usuario->usuario_fotografia;
                    $imagenData = file_get_contents($imagenPath);
                    $imagenExtension = pathinfo($usuario->usuario_fotografia, PATHINFO_EXTENSION);
                    
                    // Determinar MIME type
                    $mimeType = 'image/jpeg'; // default
                    if (strtolower($imagenExtension) === 'png') $mimeType = 'image/png';
                    elseif (strtolower($imagenExtension) === 'gif') $mimeType = 'image/gif';
                    
                    $usuarioArray['foto_base64'] = 'data:' . $mimeType . ';base64,' . base64_encode($imagenData);
                } else {
                    $usuarioArray['tiene_foto'] = false;
                    $usuarioArray['foto_base64'] = null;
                }
                
                error_log("Usuario procesado: " . print_r($usuarioArray, true));
                
                $usuariosProcesados[] = $usuarioArray;
            }

            // Respuesta exitosa
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuarios obtenidos correctamente',
                'usuarios' => $usuariosProcesados,
                'total' => count($usuariosProcesados)
            ]);
            
        } catch (Exception $e) {
            error_log("ERROR en obtenerUsuariosAPI: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error interno del servidor',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}