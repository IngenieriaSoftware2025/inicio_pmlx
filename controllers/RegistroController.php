<?php 

namespace Controllers;

use Exception;
use Model\Usuario;
use Model\ActiveRecord;
use MVC\Router;

class RegistroController extends ActiveRecord{

    public static function mostrarPagina (Router $router){
        $router->render('registro/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        $campos_requeridos = ['usuario_nom1', 'usuario_nom2', 'usuario_ape1', 'usuario_ape2', 'usuario_tel', 'usuario_direc', 'usuario_dpi', 'usuario_correo', 'usuario_contra'];
        
        foreach ($campos_requeridos as $campo) {
            if (empty($_POST[$campo])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => "El campo $campo es requerido"
                ]);
                return;
            }
        }

        // Validar primer nombre
        $_POST['usuario_nom1'] = htmlspecialchars(trim($_POST['usuario_nom1']));
        if (strlen($_POST['usuario_nom1']) < 2 || strlen($_POST['usuario_nom1']) > 50) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El primer nombre debe tener entre 2 y 50 caracteres'
            ]);
            return;
        }

        // Validar segundo nombre
        $_POST['usuario_nom2'] = htmlspecialchars(trim($_POST['usuario_nom2']));
        if (strlen($_POST['usuario_nom2']) < 2 || strlen($_POST['usuario_nom2']) > 50) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El segundo nombre debe tener entre 2 y 50 caracteres'
            ]);
            return;
        }

        // Validar primer apellido
        $_POST['usuario_ape1'] = htmlspecialchars(trim($_POST['usuario_ape1']));
        if (strlen($_POST['usuario_ape1']) < 2 || strlen($_POST['usuario_ape1']) > 50) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El primer apellido debe tener entre 2 y 50 caracteres'
            ]);
            return;
        }

        // Validar segundo apellido
        $_POST['usuario_ape2'] = htmlspecialchars(trim($_POST['usuario_ape2']));
        if (strlen($_POST['usuario_ape2']) < 2 || strlen($_POST['usuario_ape2']) > 50) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El segundo apellido debe tener entre 2 y 50 caracteres'
            ]);
            return;
        }

        // Validar DPI
        $_POST['usuario_dpi'] = preg_replace('/\D/', '', $_POST['usuario_dpi']); // Solo números
        if (strlen($_POST['usuario_dpi']) != 13) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El DPI debe tener exactamente 13 dígitos'
            ]);
            return;
        }

        // Validar teléfono
        $_POST['usuario_tel'] = filter_var($_POST['usuario_tel'], FILTER_VALIDATE_INT);
        if (!$_POST['usuario_tel'] || $_POST['usuario_tel'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El teléfono debe ser un número válido'
            ]);
            return;
        }

        // Validar dirección
        $_POST['usuario_direc'] = htmlspecialchars(trim($_POST['usuario_direc']));
        if (strlen($_POST['usuario_direc']) < 5 || strlen($_POST['usuario_direc']) > 150) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La dirección debe tener entre 5 y 150 caracteres'
            ]);
            return;
        }

        // Validar correo electrónico
        $_POST['usuario_correo'] = filter_var(trim($_POST['usuario_correo']), FILTER_SANITIZE_EMAIL);
        if (!filter_var($_POST['usuario_correo'], FILTER_VALIDATE_EMAIL) || strlen($_POST['usuario_correo']) > 50) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo electrónico es inválido o supera los 50 caracteres'
            ]);
            return;
        }

        // Validar contraseña (mínimo 10 caracteres, 1 mayúscula, 1 signo)
        $_POST['usuario_contra'] = trim($_POST['usuario_contra']);
        if (strlen($_POST['usuario_contra']) < 10) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La contraseña debe tener al menos 10 caracteres'
            ]);
            return;
        }
        
        // Validar que tenga al menos una mayúscula
        if (!preg_match('/[A-Z]/', $_POST['usuario_contra'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La contraseña debe contener al menos una letra mayúscula'
            ]);
            return;
        }
        
        // Validar que tenga al menos un signo de puntuación (coma o punto)
        if (!preg_match('/[,.]/', $_POST['usuario_contra'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La contraseña debe contener al menos un signo de puntuación (coma o punto)'
            ]);
            return;
        }

        // Validar que las contraseñas coincidan
        if (isset($_POST['confirmar_contra']) && $_POST['usuario_contra'] !== $_POST['confirmar_contra']) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Las contraseñas no coinciden'
            ]);
            return;
        }

        // Verificar que el DPI no esté registrado
        $dpiExistente = Usuario::where('usuario_dpi', $_POST['usuario_dpi']);
        if ($dpiExistente) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El DPI ya está registrado en el sistema'
            ]);
            return;
        }

        // Verificar que el correo no esté registrado
        $correoExistente = Usuario::where('usuario_correo', $_POST['usuario_correo']);
        if ($correoExistente) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo electrónico ya está registrado en el sistema'
            ]);
            return;
        }

        try {
            // Procesar fotografía (opcional)
            $fotografia = '';
            if (!empty($_POST['usuario_fotografia'])) {
                $fotografia = trim(htmlspecialchars($_POST['usuario_fotografia']));
                if (strlen($fotografia) > 2056) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'La URL de la fotografía es muy larga'
                    ]);
                    return;
                }
            }

            // Encriptar contraseña y generar token
            $_POST['usuario_contra'] = password_hash($_POST['usuario_contra'], PASSWORD_DEFAULT);
            $_POST['usuario_token'] = md5(uniqid(rand(), true));

            $data = new Usuario([
                'usuario_nom1' => $_POST['usuario_nom1'],
                'usuario_nom2' => $_POST['usuario_nom2'],
                'usuario_ape1' => $_POST['usuario_ape1'],
                'usuario_ape2' => $_POST['usuario_ape2'],
                'usuario_tel' => $_POST['usuario_tel'],
                'usuario_direc' => $_POST['usuario_direc'],
                'usuario_dpi' => $_POST['usuario_dpi'],
                'usuario_correo' => $_POST['usuario_correo'],
                'usuario_contra' => $_POST['usuario_contra'],
                'usuario_token' => $_POST['usuario_token'],
                'usuario_fotografia' => $fotografia,
                'usuario_situacion' => 1
            ]);

            // Usar guardar() en lugar de crear()
            $resultado = $data->guardar();

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
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al guardar el usuario'
                ]);
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error interno del servidor',
                'detalle' => $e->getMessage()
            ]);
        }
    }


    public static function registrarApi(Router $router)

    getHeadersApi();

    $db = User::getDB();
    $DB->BbeginTransaction();
    $data = sanitizar($_POST);

    if ($data['usu_password']) {
        # code...
    } else {
        # code...
    }
    
}