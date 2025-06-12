<?php 
require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\AppController;
use Controllers\InicioController;
use Controllers\RegistroController;
use Controllers\UsuariosController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

// Ruta principal
$router->get('/', [AppController::class,'index']);

// Rutas de inicio 
$router->get('/inicio',[InicioController::class,'mostrarPagina']);

// Rutas de registro
$router->get('/registro', [RegistroController::class, 'mostrarPagina']);
$router->post('/registro/guardarAPI', [RegistroController::class, 'guardarAPI']);
$router->get('/registro/buscarUsuariosAPI', [RegistroController::class, 'buscarUsuariosAPI']);
$router->post('/registro/verificarDPI', [RegistroController::class, 'verificarDPI']);
$router->post('/registro/verificarCorreo', [RegistroController::class, 'verificarCorreo']);

// Ruta de prueba (opcional, puedes comentarla)
$router->get('/registro/test', [RegistroController::class, 'testAPI']);

// Rutas para usuarios
$router->get('/usuarios', [UsuariosController::class, 'mostrarPagina']);
$router->get('/usuarios/obtenerAPI', [UsuariosController::class, 'obtenerUsuariosAPI']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();