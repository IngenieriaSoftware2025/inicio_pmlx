<?php 

namespace Controllers;

use Exception;
use Model\Usuario;
use MVC\Router;

class RegistroController {

    public static function mostrarPagina (Router $router){
        $router->render('registro/index', [], 'layout/layout');
    }

    public static function guardarAPI()
    {
        // Configurar headers para API
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type');
        
        try {
            // Debug inicial
            error_log("=== INICIO guardarAPI ===");
            
            // Verificar que sea POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Método no permitido'
                ]);
                return;
            }

            // Verificar campos requeridos
            $camposRequeridos = ['usuario_nom1', 'usuario_nom2', 'usuario_ape1', 'usuario_ape2', 
                               'usuario_tel', 'usuario_direc', 'usuario_dpi', 'usuario_correo', 
                               'usuario_contra', 'confirmar_contra'];
            
            foreach ($camposRequeridos as $campo) {
                if (!isset($_POST[$campo]) || empty(trim($_POST[$campo]))) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => "El campo {$campo} es requerido"
                    ]);
                    return;
                }
            }

            // Sanitizar datos
            $_POST['usuario_nom1'] = ucwords(strtolower(trim($_POST['usuario_nom1'])));
            $_POST['usuario_nom2'] = ucwords(strtolower(trim($_POST['usuario_nom2'])));
            $_POST['usuario_ape1'] = ucwords(strtolower(trim($_POST['usuario_ape1'])));
            $_POST['usuario_ape2'] = ucwords(strtolower(trim($_POST['usuario_ape2'])));
            $_POST['usuario_direc'] = ucwords(strtolower(trim($_POST['usuario_direc'])));
            $_POST['usuario_correo'] = strtolower(trim($_POST['usuario_correo']));
            
            // SOLUCIÓN DEL TELÉFONO - Mantenerlo como STRING, no como INTEGER
            $_POST['usuario_tel'] = preg_replace('/\D/', '', $_POST['usuario_tel']);
            if (strlen($_POST['usuario_tel']) != 8) {
                http_response_code(400);
                echo json_encode(['codigo' => 0, 'mensaje' => 'El teléfono debe tener 8 dígitos']);
                return;
            }
            // NO convertir a entero - dejarlo como string
            
            $_POST['usuario_dpi'] = preg_replace('/\D/', '', $_POST['usuario_dpi']);
            if (strlen($_POST['usuario_dpi']) != 13) {
                http_response_code(400);
                echo json_encode(['codigo' => 0, 'mensaje' => 'El DPI debe tener 13 dígitos']);
                return;
            }
            
            if (!filter_var($_POST['usuario_correo'], FILTER_VALIDATE_EMAIL)){
                http_response_code(400);
                echo json_encode(['codigo' => 0, 'mensaje' => 'Email inválido']);
                return;
            }

            // Verificar duplicados
            $usuariosConCorreo = Usuario::where('usuario_correo', $_POST['usuario_correo']);
            if (!empty($usuariosConCorreo)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El correo electrónico ya está registrado'
                ]);
                return;
            }

            $usuariosConDPI = Usuario::where('usuario_dpi', $_POST['usuario_dpi']);
            if (!empty($usuariosConDPI)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El DPI ya está registrado'
                ]);
                return;
            }
            
            // Validar contraseña
            if (strlen($_POST['usuario_contra']) < 10) {
                http_response_code(400);
                echo json_encode(['codigo' => 0, 'mensaje' => 'La contraseña debe tener al menos 10 caracteres']);
                return;
            }

            if (!preg_match('/[A-Z]/', $_POST['usuario_contra'])) {
                http_response_code(400);
                echo json_encode(['codigo' => 0, 'mensaje' => 'La contraseña debe tener al menos una mayúscula']);
                return;
            }

            if (!preg_match('/[,.]/', $_POST['usuario_contra'])) {
                http_response_code(400);
                echo json_encode(['codigo' => 0, 'mensaje' => 'La contraseña debe tener al menos un signo (coma o punto)']);
                return;
            }
            
            if ($_POST['usuario_contra'] !== $_POST['confirmar_contra']) {
                http_response_code(400);
                echo json_encode(['codigo' => 0, 'mensaje' => 'Las contraseñas no coinciden']);
                return;
            }
            
            // Manejo de fotografía
            $rutaFotografia = null;
            if (isset($_FILES['usuario_fotografia']) && $_FILES['usuario_fotografia']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['usuario_fotografia'];
                $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array($fileExtension, $allowed)) {
                    http_response_code(400);
                    echo json_encode(['codigo' => 0, 'mensaje' => 'Solo se permiten archivos JPG, PNG, JPEG o GIF']);
                    return;
                }
                
                if ($file['size'] >= 2097152) {
                    http_response_code(400);
                    echo json_encode(['codigo' => 0, 'mensaje' => 'La imagen debe pesar menos de 2MB']);
                    return;
                }
                
                $dpi = $_POST['usuario_dpi'];
                $uploadDir = __DIR__ . "/../../storage/fotosUsuarios/";
                
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $rutaFotografia = "storage/fotosUsuarios/$dpi.$fileExtension";
                $rutaCompleta = $uploadDir . "$dpi.$fileExtension";
                
                if (!move_uploaded_file($file['tmp_name'], $rutaCompleta)) {
                    http_response_code(500);
                    echo json_encode(['codigo' => 0, 'mensaje' => 'Error al subir la fotografía']);
                    return;
                }
            }
            
            // Preparar datos finales
            $_POST['usuario_token'] = uniqid();
            $_POST['usuario_contra'] = password_hash($_POST['usuario_contra'], PASSWORD_DEFAULT);
            $_POST['usuario_fotografia'] = $rutaFotografia;
            $_POST['usuario_situacion'] = 1;
            
            // NO enviar fechas - dejar que Informix use DEFAULT TODAY
            unset($_POST['confirmar_contra']);
            
            error_log("Datos finales: " . print_r($_POST, true));
            
            // USAR QUERY MANUAL PARA CONTROLAR EXACTAMENTE QUÉ SE ENVÍA
            $db = \Model\ActiveRecord::getDB();
            
            $query = "INSERT INTO usuario (
                usuario_nom1, usuario_nom2, usuario_ape1, usuario_ape2,
                usuario_tel, usuario_direc, usuario_dpi, usuario_correo,
                usuario_contra, usuario_token, usuario_fotografia, usuario_situacion
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $valores = [
                $_POST['usuario_nom1'],
                $_POST['usuario_nom2'],
                $_POST['usuario_ape1'],
                $_POST['usuario_ape2'],
                $_POST['usuario_tel'], // Como string, no como integer
                $_POST['usuario_direc'],
                $_POST['usuario_dpi'],
                $_POST['usuario_correo'],
                $_POST['usuario_contra'],
                $_POST['usuario_token'],
                $rutaFotografia,
                1
            ];
            
            error_log("Query: " . $query);
            error_log("Valores: " . print_r($valores, true));
            
            $stmt = $db->prepare($query);
            $resultado = $stmt->execute($valores);
            
            if($resultado){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Usuario registrado correctamente'
                ]);
            } else {
                if ($rutaFotografia && file_exists(__DIR__ . "/../../" . $rutaFotografia)) {
                    unlink(__DIR__ . "/../../" . $rutaFotografia);
                }
                
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al registrar en la base de datos'
                ]);
            }
            
        } catch (Exception $e) {
            error_log("ERROR: " . $e->getMessage());
            
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error interno: ' . $e->getMessage()
            ]);
        }
    }

    public static function testAPI()
    {
        header('Content-Type: application/json');
        
        try {
            $usuarios = Usuario::all();
            
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Conexión exitosa',
                'total_usuarios' => count($usuarios)
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public static function verificarCorreo()
    {
        header('Content-Type: application/json');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['codigo' => 0, 'mensaje' => 'Método no permitido']);
                return;
            }

            $correo = $_POST['correo'] ?? '';
            if (empty($correo)) {
                echo json_encode(['codigo' => 0, 'mensaje' => 'Correo requerido']);
                return;
            }

            $usuarios = Usuario::where('usuario_correo', $correo);
            
            echo json_encode([
                'codigo' => empty($usuarios) ? 1 : 0,
                'existe' => !empty($usuarios),
                'mensaje' => empty($usuarios) ? 'Correo disponible' : 'El correo ya está registrado'
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['codigo' => 0, 'mensaje' => 'Error: ' . $e->getMessage()]);
        }
    }

    public static function verificarDPI()
    {
        header('Content-Type: application/json');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['codigo' => 0, 'mensaje' => 'Método no permitido']);
                return;
            }

            $dpi = $_POST['dpi'] ?? '';
            if (empty($dpi)) {
                echo json_encode(['codigo' => 0, 'mensaje' => 'DPI requerido']);
                return;
            }

            $usuarios = Usuario::where('usuario_dpi', $dpi);
            
            echo json_encode([
                'codigo' => empty($usuarios) ? 1 : 0,
                'existe' => !empty($usuarios),
                'mensaje' => empty($usuarios) ? 'DPI disponible' : 'El DPI ya está registrado'
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['codigo' => 0, 'mensaje' => 'Error: ' . $e->getMessage()]);
        }
    }
}