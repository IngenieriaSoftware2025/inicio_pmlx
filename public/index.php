<?php 
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;
use Controllers\AppController;
use Controllers\InicioController;
use Controllers\RegistroController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class,'index']);




//inicio 
$router->get('/inicio',[InicioController::class,'mostrarPagina']);


// $router->get('/usuario', [UsuarioController::class, 'renderizarPAgina']);
// $router->get('/usuarios/buscarAPI', [UsuarioController::class, 'buscarAPI']);
// $router->post('/usuarios/modificarAPI', [UsuarioController::class, 'modificarAPI']);
// $router->get('/usuarios/eliminarAPI', [UsuarioController::class, 'eliminarAPI']);



//registro 
$router->get('/registro',[RegistroController::class,'mostrarPagina']);
$router->post('/registro/guardarAPI', [RegistroController::class, 'guardarAPI']);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
