<?php
namespace config;

use PDO;
use PDOException;

class INITDB
{
    private static ?INITDB $instance = null;
    private ?PDO $conn = null;

    private string $host = 'sql302.infinityfree.com';
    private string $dbName = 'if0_40346243_hmrs';
    private string $username = 'if0_40346243';
    private string $password = 'ZynCO1OqzuFBV';
    private int $port = 3306;

    private function __construct()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbName};port={$this->port};charset=utf8mb4";

            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            throw new PDOException("Database connection failed: " . $e->getMessage());
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
