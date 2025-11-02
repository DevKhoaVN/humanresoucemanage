<?php


$url = isset($_GET['url']) ? $_GET['url'] : 'index';
$urlPaths = explode('/',  trim($url,'/'));
echo $url;


$controllerName = ucfirst($urlPaths[0]).'Controller';
$actionName =  trim(isset($urlPaths[1]) ? $urlPaths[1] : 'index');


$controllerFile = __DIR__.'/controllers/'.$controllerName.'.php';

echo "filename". $controllerName;

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