<?php

namespace Controllers;

use Exception;
use MVC\Router;

class AppController {
    public static function index(Router $router){
        $router->render('pages/index', []);
        //$router->render('login/index', [], 'layout/layoutLogin');

    }



    public static function login() {
        getHeadersApi();

        try{

            $usuario = filter_var[$_POST(usu_codigo)]
            
        }catch(Exception $e){
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Erro al intentar loguear',
                'detalle' => $e->getMessage()
            ]);
}
}
}