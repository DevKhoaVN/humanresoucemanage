<?php
require_once __DIR__ .'/config/INITDB.php';
require_once __DIR__. '/router.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__. '/', '.env');
$dotenv->load();

?>
