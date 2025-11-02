<?php
require_once  __DIR__ .'/vendor/autoload.php';
require_once __DIR__ .'/config/INITDB.php';
require_once __DIR__. '/router.php';


echo "day la trang index";
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__. '/', '.env');
$dotenv->load();

?>
