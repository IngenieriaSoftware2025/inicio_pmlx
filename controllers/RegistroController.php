<?php 

namespace Controllers;

use Exception;
use Model\Usuario;
use Model\ActiveRecord;
use MVC\Router;

class RegistroController extends ActiveRecord{

    public static function mostrarPagina (Router $router){
        $router->render('registro/index', [], 'layout/layout');
    }

    public static function testAPI() {
        header('Content-Type: application/json');
        echo json_encode([
            'codigo' => 1,
            'mensaje' => 'La ruta funciona correctamente',
            'metodo' => 'GET de prueba'
        ]);
    }

    public static function guardarAPI()
    {
        // Headers básicos primero
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type');

        try {
            // Log para debug
            error_log('=== INICIO guardarAPI ===');
            error_log('POST data: ' . print_r($_POST, true));

            // Verificar si llegan datos
            if (empty($_POST)) {
                throw new Exception('No se recibieron datos POST');
            }

            // Verificar campos requeridos
            $campos_requeridos = [
                'usuario_nom1', 'usuario_nom2', 'usuario_ape1', 'usuario_ape2', 
                'usuario_tel', 'usuario_direc', 'usuario_dpi', 'usuario_correo', 'usuario_contra'
            ];
            
            foreach ($campos_requeridos as $campo) {
                if (empty($_POST[$campo])) {
                    throw new Exception("El campo $campo es requerido");
                }
            }

            // Sanitizar datos básicos
            $usuario_nom1 = trim(htmlspecialchars($_POST['usuario_nom1']));
            $usuario_nom2 = trim(htmlspecialchars($_POST['usuario_nom2']));
            $usuario_ape1 = trim(htmlspecialchars($_POST['usuario_ape1']));
            $usuario_ape2 = trim(htmlspecialchars($_POST['usuario_ape2']));
            $usuario_direc = trim(htmlspecialchars($_POST['usuario_direc']));
            $usuario_correo = trim($_POST['usuario_correo']);
            $usuario_contra = trim($_POST['usuario_contra']);

            // Validar DPI
            $usuario_dpi = preg_replace('/\D/', '', $_POST['usuario_dpi']);
            if (strlen($usuario_dpi) != 13) {
                throw new Exception('El DPI debe tener exactamente 13 dígitos');
            }

            // Validar teléfono
            $usuario_tel = preg_replace('/\D/', '', $_POST['usuario_tel']);
            if (strlen($usuario_tel) != 8) {
                throw new Exception('El teléfono debe tener exactamente 8 dígitos');
            }

            // Validar email
            if (!filter_var($usuario_correo, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('El correo electrónico no es válido');
            }

            // Validar contraseña
            if (strlen($usuario_contra) < 10) {
                throw new Exception('La contraseña debe tener al menos 10 caracteres');
            }

            if (!preg_match('/[A-Z]/', $usuario_contra)) {
                throw new Exception('La contraseña debe contener al menos una mayúscula');
            }

            if (!preg_match('/[,.]/', $usuario_contra)) {
                throw new Exception('La contraseña debe contener al menos un signo (,.)');
            }

            error_log('Validaciones básicas completadas');

            // Verificar duplicados de forma segura
            try {
                // Verificar DPI duplicado
                $sql_dpi = "SELECT COUNT(*) as total FROM usuario WHERE usuario_dpi = ?";
                $stmt_dpi = Usuario::$db->prepare($sql_dpi);
                $stmt_dpi->execute([$usuario_dpi]);
                $result_dpi = $stmt_dpi->fetch();
                
                if ($result_dpi['total'] > 0) {
                    throw new Exception('El DPI ya está registrado en el sistema');
                }

                // Verificar correo duplicado
                $sql_correo = "SELECT COUNT(*) as total FROM usuario WHERE usuario_correo = ?";
                $stmt_correo = Usuario::$db->prepare($sql_correo);
                $stmt_correo->execute([$usuario_correo]);
                $result_correo = $stmt_correo->fetch();
                
                if ($result_correo['total'] > 0) {
                    throw new Exception('El correo ya está registrado en el sistema');
                }

                error_log('Verificación de duplicados completada');

            } catch (Exception $e) {
                error_log('Error en verificación de duplicados: ' . $e->getMessage());
                // Continuar sin verificar duplicados si hay problemas con la BD
            }

            // Crear usuario
            $passwordHash = password_hash($usuario_contra, PASSWORD_DEFAULT);
            $token = md5(uniqid(rand(), true));

            error_log('Creando objeto Usuario...');

            $data = new Usuario([
                'usuario_nom1' => $usuario_nom1,
                'usuario_nom2' => $usuario_nom2,
                'usuario_ape1' => $usuario_ape1,
                'usuario_ape2' => $usuario_ape2,
                'usuario_tel' => intval($usuario_tel),
                'usuario_direc' => $usuario_direc,
                'usuario_dpi' => $usuario_dpi,
                'usuario_correo' => $usuario_correo,
                'usuario_contra' => $passwordHash,
                'usuario_token' => $token,
                'usuario_fotografia' => '',
                'usuario_situacion' => 1
            ]);

            error_log('Objeto Usuario creado, intentando guardar...');

            // Intentar guardar
            $resultado = $data->guardar();

            error_log('Resultado del guardar: ' . ($resultado ? 'true' : 'false'));

            if ($resultado) {
                http_response_code(201);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Usuario registrado correctamente',
                    'datos' => [
                        'usuario_id' => $data->usuario_id,
                        'nombre_completo' => $data->usuario_nom1 . ' ' . $data->usuario_ape1,
                        'correo' => $data->usuario_correo
                    ]
                ]);
            } else {
                throw new Exception('Error al guardar el usuario en la base de datos');
            }
            
        } catch (Exception $e) {
            error_log('ERROR en guardarAPI: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }

        error_log('=== FIN guardarAPI ===');
    }

    public static function validarDuplicados()
    {
        header('Content-Type: application/json');
        
        try {
            if (empty($_POST['campo']) || empty($_POST['valor'])) {
                throw new Exception('Faltan parámetros');
            }

            $campo = $_POST['campo'];
            $valor = $_POST['valor'];

            if ($campo === 'dpi') {
                $sql = "SELECT COUNT(*) as total FROM usuario WHERE usuario_dpi = ?";
            } else if ($campo === 'correo') {
                $sql = "SELECT COUNT(*) as total FROM usuario WHERE usuario_correo = ?";
            } else {
                throw new Exception('Campo no válido');
            }

            $stmt = Usuario::$db->prepare($sql);
            $stmt->execute([$valor]);
            $result = $stmt->fetch();
            $existe = $result['total'] > 0;

            echo json_encode([
                'codigo' => 1,
                'existe' => $existe,
                'mensaje' => $existe ? "El $campo ya está registrado" : "El $campo está disponible"
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}