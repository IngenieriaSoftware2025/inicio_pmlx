<?php 

namespace Controllers;

use Exception;
use Model\Usuario;
use Model\ActiveRecord;
use MVC\Router;

class InicioController extends ActiveRecord{

    public static function mostrarPagina (Router $router){
        $router->render('inicio/index', [], 'layout/layouinicio');  //cambiar aca solo para poner el login 
    }
}