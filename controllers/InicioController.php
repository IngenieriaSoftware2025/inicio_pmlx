<?php 

namespace Controllers;

use Exception;
use Model\Usuario;
use Model\ActiveRecord;
use MVC\Router;

class InicioController extends ActiveRecord{

    public static function mostrarPagina (Router $router){
        $router->render('inicio/index', [], 'layout/layout');
    }

    public static function guardarInicio()
    {
        getHeadersApi();
        
        // PROBLEMA 1: Esta línea hace echo y termina la ejecución
        // echo json_encode($_POST); // COMENTADA
        
        // Validar que lleguen los datos
        if(empty($_POST['nombres']) || empty($_POST['apellidos']) || empty($_POST['telefono']) || empty($_POST['correo'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Faltan campos obligatorios'
            ]);
            return;
        }

        // Validar nombres
        $_POST['nombres'] = ucwords(strtolower(trim(htmlspecialchars($_POST['nombres']))));
        $cantidad_nombre = strlen($_POST['nombres']);
        if($cantidad_nombre < 2 ){
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre es inválido'
            ]);
            return; // AGREGADO return
        }

        // Validar apellidos
        $_POST['apellidos'] = ucwords(strtolower(trim(htmlspecialchars($_POST['apellidos']))));
        $cantidad_apellido = strlen($_POST['apellidos']); // CORREGIDO: era $_POST['nombres']
        if($cantidad_apellido < 2 ){
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El apellido es inválido'
            ]);
            return; // AGREGADO return
        }

        // Validar teléfono
        $_POST['telefono'] = filter_var($_POST['telefono'], FILTER_SANITIZE_NUMBER_INT); // CORREGIDO: faltaba coma
        if(strlen($_POST['telefono']) != 8){
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Teléfono debe tener 8 números'
            ]);
            return; // AGREGADO return
        }

        // Procesar NIT
        $_POST['nit'] = trim(htmlspecialchars($_POST['nit'])); 

        // Validar correo
        $_POST['correo'] = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL); // CORREGIDO: faltaba coma
        if(!filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)){ // CORREGIDO: lógica invertida
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo electrónico es inválido'
            ]);
            return; // AGREGADO return
        }

        // Si llegamos aquí, todos los datos son válidos
        try{
            $clientes = new Clientes([
                'nombres' => $_POST['nombres'],
                'apellidos' => $_POST['apellidos'],
                'telefono' => $_POST['telefono'],
                'nit' => $_POST['nit'],
                'correo' => $_POST['correo'],
                'situacion' => 1
            ]);

            $crear = $clientes->crear();
            
            if($crear){ // Verificar si se creó correctamente
                http_response_code(200); // CORREGIDO: era 400
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Cliente guardado exitosamente'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al guardar el cliente'
                ]);
            }

        } catch (Exception $e){
            http_response_code(500); // CORREGIDO: 500 para errores del servidor
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}