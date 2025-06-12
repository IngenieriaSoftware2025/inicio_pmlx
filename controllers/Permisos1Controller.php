<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Permisos1;

class Permisos1Controller extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('permisos1/index', []);
    }

    public static function guardarAPI()
    {
        header('Content-Type: application/json');
        
        try {
            // Verificar que sea POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Método no permitido'
                ]);
                exit;
            }

            // Verificar que lleguen los datos
            if (empty($_POST['permiso_nombre']) || empty($_POST['permiso_clave']) || 
                empty($_POST['permiso_desc']) || empty($_POST['permiso_app_id'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Faltan datos requeridos'
                ]);
                exit;
            }

            $_POST['permiso_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['permiso_nombre']))));
            
            $cantidad_nombre = strlen($_POST['permiso_nombre']);
            
            if ($cantidad_nombre < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Nombre del permiso debe tener más de 1 carácter'
                ]);
                exit;
            }
            
            $_POST['permiso_clave'] = strtoupper(trim(htmlspecialchars($_POST['permiso_clave'])));
            
            $cantidad_clave = strlen($_POST['permiso_clave']);
            
            if ($cantidad_clave < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Clave del permiso debe tener más de 1 carácter'
                ]);
                exit;
            }
            
            $_POST['permiso_desc'] = ucwords(strtolower(trim(htmlspecialchars($_POST['permiso_desc']))));
            
            $cantidad_desc = strlen($_POST['permiso_desc']);
            
            if ($cantidad_desc < 5) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Descripción debe tener más de 4 caracteres'
                ]);
                exit;
            }
            
            $_POST['permiso_app_id'] = filter_var($_POST['permiso_app_id'], FILTER_SANITIZE_NUMBER_INT);
            
            if ($_POST['permiso_app_id'] <= 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar una aplicación válida'
                ]);
                exit;
            }

            $_POST['permiso_fecha'] = date('Y-m-d H:i:s');
            $_POST['permiso_situacion'] = 1;
            
            $permiso = new Permisos1($_POST);
            $resultado = $permiso->crear();

            if($resultado['resultado'] == 1){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Permiso creado correctamente',
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al crear el permiso',
                    'detalle' => $resultado
                ]);
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error interno del servidor',
                'detalle' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine()
            ]);
        }
        exit;
    }

    public static function buscarAPI()
    {
        header('Content-Type: application/json');
        
        try {
            $app_id = isset($_GET['app_id']) ? $_GET['app_id'] : null;
            $buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : null;

            $condiciones = ["p.permiso_situacion = 1"];

            if ($app_id) {
                $condiciones[] = "p.permiso_app_id = " . filter_var($app_id, FILTER_SANITIZE_NUMBER_INT);
            }

            if ($buscar) {
                $buscar = htmlspecialchars($buscar);
                $condiciones[] = "(p.permiso_nombre LIKE '%$buscar%' OR p.permiso_clave LIKE '%$buscar%' OR p.permiso_desc LIKE '%$buscar%')";
            }

            $where = implode(" AND ", $condiciones);
            $sql = "SELECT 
                        p.*,
                        a.app_nombre_corto
                    FROM permiso1 p 
                    INNER JOIN aplicacion a ON p.permiso_app_id = a.app_id 
                    WHERE $where 
                    ORDER BY p.permiso_fecha DESC";
            
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Permisos obtenidos correctamente',
                'data' => $data,
                'total' => count($data)
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los permisos',
                'detalle' => $e->getMessage(),
                'sql_error' => 'Posible error en la consulta SQL'
            ]);
        }
        exit;
    }

    public static function modificarAPI()
    {
        header('Content-Type: application/json');
        
        try {
            $id = filter_var($_POST['permiso_id'], FILTER_SANITIZE_NUMBER_INT);
            
            if ($id <= 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de permiso inválido'
                ]);
                exit;
            }

            $_POST['permiso_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['permiso_nombre']))));
            $_POST['permiso_clave'] = strtoupper(trim(htmlspecialchars($_POST['permiso_clave'])));
            $_POST['permiso_desc'] = ucwords(strtolower(trim(htmlspecialchars($_POST['permiso_desc']))));
            $_POST['permiso_app_id'] = filter_var($_POST['permiso_app_id'], FILTER_SANITIZE_NUMBER_INT);

            $data = Permisos1::find($id);
            $data->sincronizar([
                'permiso_app_id' => $_POST['permiso_app_id'],
                'permiso_nombre' => $_POST['permiso_nombre'],
                'permiso_clave' => $_POST['permiso_clave'],
                'permiso_desc' => $_POST['permiso_desc'],
                'permiso_situacion' => 1
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El permiso ha sido modificado exitosamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar el permiso',
                'detalle' => $e->getMessage(),
            ]);
        }
        exit;
    }

    public static function EliminarAPI()
    {
        header('Content-Type: application/json');
        
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            
            if ($id <= 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de permiso inválido'
                ]);
                exit;
            }

            $ejecutar = Permisos1::EliminarPermiso($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El permiso ha sido eliminado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar el permiso',
                'detalle' => $e->getMessage(),
            ]);
        }
        exit;
    }

    public static function buscarAplicacionesAPI()
    {
        header('Content-Type: application/json');
        
        try {
            $sql = "SELECT app_id, app_nombre_corto FROM aplicacion WHERE app_situacion = 1 ORDER BY app_nombre_corto";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Aplicaciones obtenidas correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las aplicaciones',
                'detalle' => $e->getMessage(),
            ]);
        }
        exit;
    }

    public static function obtenerPermisoPorIdAPI()
    {
        header('Content-Type: application/json');
        
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            
            if ($id <= 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de permiso inválido'
                ]);
                exit;
            }

            $sql = "SELECT p.*, a.app_nombre_corto 
                    FROM permiso1 p 
                    INNER JOIN aplicacion a ON p.permiso_app_id = a.app_id 
                    WHERE p.permiso_id = $id";
            $data = self::fetchArray($sql);

            if (count($data) > 0) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Permiso obtenido correctamente',
                    'data' => $data[0]
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Permiso no encontrado'
                ]);
            }

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener el permiso',
                'detalle' => $e->getMessage(),
            ]);
        }
        exit;
    }

    public static function renderInicio(Router $router){
        $router->render('pages/index', [], 'layouts/menu');
    }
}