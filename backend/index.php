<?php
header('Content-Type: application/json');

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/INITDB.php';
require_once __DIR__ . '/middleware/AuthMiddleware.php';

use Dotenv\Dotenv;
use middleware\AuthMiddleware;


$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


$url = $_GET['url'] ?? 'index';
$urlPaths = explode('/', trim($url, '/'));
$route = trim(strtolower($urlPaths[1] ?? ''));


$publicRoutes = ['login', 'register', 'logout'];

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($method !== 'OPTIONS' && !in_array($route, $publicRoutes)) {
    AuthMiddleware::handle();
}

require_once __DIR__ . '/router.php';
