<?php 
require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\AppController;
use Controllers\RegistroController;
use Controllers\AplicacionController;
use Controllers\AsignacionPermisos1Controller;
use Controllers\LoginController;
use Controllers\Permisos1Controller;

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



//permisos1
$router->get('/permisos1', [Permisos1Controller::class, 'renderizarPagina']);
$router->post('/permisos1/guardarAPI', [Permisos1Controller::class, 'guardarAPI']);
$router->get('/permisos1/buscarAPI', [Permisos1Controller::class, 'buscarAPI']);
$router->post('/permisos1/modificarAPI', [Permisos1Controller::class, 'modificarAPI']);
$router->get('/permisos1/eliminar', [Permisos1Controller::class, 'EliminarAPI']);
$router->get('/permisos1/buscarAplicacionesAPI', [Permisos1Controller::class, 'buscarAplicacionesAPI']);
$router->get('/permisos1/obtenerPermisoPorIdAPI', [Permisos1Controller::class, 'obtenerPermisoPorIdAPI']);


//asignacion-permisos1
$router->get('/asignacion_permisos1', [AsignacionPermisos1Controller::class, 'renderizarPagina']);
$router->post('/asignacion_permisos1/guardarAPI', [AsignacionPermisos1Controller::class, 'guardarAPI']);
$router->get('/asignacion_permisos1/buscarAPI', [AsignacionPermisos1Controller::class, 'buscarAPI']);
$router->post('/asignacion_permisos1/modificarAPI', [AsignacionPermisos1Controller::class, 'modificarAPI']);
$router->get('/asignacion_permisos1/eliminar', [AsignacionPermisos1Controller::class, 'EliminarAPI']);
$router->get('/asignacion_permisos1/buscarAplicacionesAPI', [AsignacionPermisos1Controller::class, 'buscarAplicacionesAPI']);
$router->get('/asignacion_permisos1/buscarUsuariosAPI', [AsignacionPermisos1Controller::class, 'buscarUsuariosAPI']);
$router->get('/asignacion_permisos1/buscarPermisosAPI', [AsignacionPermisos1Controller::class, 'buscarPermisosAPI']);
$router->get('/asignacion_permisos1/verificarPermisoAPI', [AsignacionPermisos1Controller::class, 'verificarPermisoAPI']);
$router->get('/asignacion_permisos1/obtenerPorUsuarioAPI', [AsignacionPermisos1Controller::class, 'obtenerPorUsuarioAPI']);
$router->get('/asignacion_permisos1/estadisticasAPI', [AsignacionPermisos1Controller::class, 'estadisticasAPI']);


$router->comprobarRutas();