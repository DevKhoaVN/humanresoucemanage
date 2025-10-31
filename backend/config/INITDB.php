<?php
namespace config;

require_once __DIR__ .'/../utils/Logger.php';
require_once __DIR__ . '/../exception/CustomException.php';
require_once __DIR__ . '/../exception/ErrorCode.php';
use PDO;
use PDOException;
use exception\DatabaseException;

use utils\Logger;

class INITDB
{
    private static ?INITDB $instance = null;
    private ?PDO $conn = null;

    private string $host = 'mysql';
    private string $dbName = 'humanresource';
    private string $username = 'user';
    private string $password = '123456';
    private int $port = 3306;

    private function __construct()
    {
        $logger = Logger::getInstance();

        try {

            $logger->info("Database connecting...");
            $dsn = "mysql:host={$this->host};dbname={$this->dbName};port={$this->port};charset=utf8mb4";

            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $logger->info("Database connected.");

        } catch (PDOException $e) {
            throw new DatabaseException("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance(): INITDB
    {
        if (self::$instance === null) {
            self::$instance = new INITDB();
        }
        return self::$instance;
    }

    public  function getConnection(): PDO
    {
        return $this->conn;
    }
}
