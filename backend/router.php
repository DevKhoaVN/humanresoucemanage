<?php

$logger = \utils\Logger::getInstance();

$url = isset($_GET['url']) ? $_GET['url'] : null;
$urlPaths = explode('/',  trim($url,'/'));


$controllerName = ucfirst($urlPaths[0]).'Controller';
$actionName = isset($urlPaths[1]) ? $urlPaths[1] : 'index';


$controllerFile = __DIR__.'/controllers/'.$controllerName.'.php';


echo "controllername : ".$controllerFile."<br>"  ;

try {
    // check controller file
    if(file_exists($controllerFile)) {


        $logger->info("Loading controller: ".$controllerName);

        require_once $controllerFile;
        $controller = new $controllerName();

        // check method exist
        if(method_exists($controller, $actionName)) {
            $controller->$actionName();
        }
        $logger->info("Action: ".$actionName);

    }
}catch (Exception $e) {
    $logger->error($e->getMessage());
}

?>