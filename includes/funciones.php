<?php

function debuguear($variable) {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) {
    $s = htmlspecialchars($html);
    return $s;
}

// Función que revisa que el usuario este autenticado
function isAuth() {
    session_start();
    if(!isset($_SESSION['login'])) {
        header('Location: /');
    }
}

function isAuthApi() {
    getHeadersApi();
    session_start();
    if(!isset($_SESSION['auth_user'])) {
        echo json_encode([    
            "mensaje" => "No esta autenticado",
            "codigo" => 4,
        ]);
        exit;
    }
}

function isNotAuth(){
    session_start();
    if(isset($_SESSION['auth'])) {
        header('Location: /auth/');
    }
}

function hasPermission(array $permisos){
    $comprobaciones = [];
    foreach ($permisos as $permiso) {
        $comprobaciones[] = !isset($_SESSION[$permiso]) ? false : true;
    }

    if(array_search(true, $comprobaciones) !== false){}else{
        header('Location: /');
    }
}

function hasPermissionApi(array $permisos){
    getHeadersApi();
    $comprobaciones = [];
    foreach ($permisos as $permiso) {
        $comprobaciones[] = !isset($_SESSION[$permiso]) ? false : true;
    }

    if(array_search(true, $comprobaciones) !== false){}else{
        echo json_encode([     
            "mensaje" => "No tiene permisos",
            "codigo" => 4,
        ]);
        exit;
    }
}

// FUNCIÓN ACTUALIZADA - Reemplaza tu función getHeadersApi() actual
function getHeadersApi(){
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header("Content-Type: application/json; charset=UTF-8");
    
    // Manejar preflight requests
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
}

// NUEVA FUNCIÓN - Agregar esta función para validar formularios
function validarFormularioPHP($campos_requeridos, $excluir = []) {
    foreach ($campos_requeridos as $campo) {
        if (in_array($campo, $excluir)) {
            continue;
        }
        
        if (!isset($_POST[$campo]) || empty(trim($_POST[$campo]))) {
            return false;
        }
    }
    
    return true;
}

function asset($ruta){
    return "/". $_ENV['APP_NAME']."/public/" . $ruta;
}