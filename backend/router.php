<?php

// === CORS headers ===
$allowed_origins = ['http://localhost:5500', 'http://127.0.0.1:5500'];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
}

header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$url = isset($_GET['url']) ? $_GET['url'] : 'index';
$urlPaths = explode('/',  trim($url,'/'));

$controllerName = ucfirst($urlPaths[0]).'Controller';
$actionName =  trim(isset($urlPaths[1]) ? $urlPaths[1] : 'index');

$controllerFile = __DIR__.'/controllers/'.$controllerName.'.php';


try {
    // check controller file
    if(file_exists($controllerFile)) {

        require_once $controllerFile;

        $controller = new $controllerName();


        // check method exist
        if(method_exists($controller, $actionName)) {
            $controller->$actionName();
        }else{
            echo "Action $actionName not exist";
        }

    }else{
        echo "controller $controllerName not exist";
    }
}catch (Exception $e) {
   throw new Exception($e->getMessage());
}

?>