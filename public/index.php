<?php 
require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\AppController;
use Controllers\RegistroController;
use Controllers\AplicacionController;
use Controllers\PermisosController;
use Controllers\LoginController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

// Rutas principales van al login
$router->get('/inicio_pmlx', [LoginController::class,'renderizarPagina']);
$router->get('/', [LoginController::class,'renderizarPagina']);

// Rutas del login
$router->get('/login', [LoginController::class, 'renderizarPagina']);
$router->post('/login', [LoginController::class, 'login']);

// Ruta para el índice principal (menu con tarjetas)
$router->get('/inicio', [AppController::class,'index']);

//usuarios
$router->get('/usuarios', [RegistroController::class, 'renderizarPagina']);
$router->post('/usuarios/guardarAPI', [RegistroController::class, 'guardarAPI']);
$router->get('/usuarios/buscarAPI', [RegistroController::class, 'buscarAPI']);
$router->post('/usuarios/modificarAPI', [RegistroController::class, 'modificarAPI']);
$router->get('/usuarios/eliminar', [RegistroController::class, 'EliminarAPI']);

//aplicaciones
$router->get('/aplicacion', [AplicacionController::class, 'renderizarPagina']);
$router->post('/aplicacion/guardarAPI', [AplicacionController::class, 'guardarAPI']);
$router->get('/aplicacion/buscarAPI', [AplicacionController::class, 'buscarAPI']);
$router->post('/aplicacion/modificarAPI', [AplicacionController::class, 'modificarAPI']);
$router->get('/aplicacion/eliminar', [AplicacionController::class, 'EliminarAPI']);

//permisos
$router->get('/permisos', [PermisosController::class, 'renderizarPagina']);
$router->post('/permisos/guardarAPI', [PermisosController::class, 'guardarAPI']);
$router->get('/permisos/buscarAPI', [PermisosController::class, 'buscarAPI']);
$router->post('/permisos/modificarAPI', [PermisosController::class, 'modificarAPI']);
$router->get('/permisos/eliminar', [PermisosController::class, 'EliminarAPI']);
$router->get('/permisos/buscarAplicacionesAPI', [PermisosController::class, 'buscarAplicacionesAPI']);
$router->get('/permisos/buscarUsuariosAPI', [PermisosController::class, 'buscarUsuariosAPI']);

$router->comprobarRutas();