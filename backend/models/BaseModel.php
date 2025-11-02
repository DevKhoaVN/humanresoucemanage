<?php

namespace models;
require_once __DIR__ . '/../config/INITDB.php';

use config\INITDB;
use PDO;
use PDOException;

Abstract class BaseModel
{
  protected static string $tableName = '';

  public static function findById(int $id): ?static
  {
      $pdo = INITDB::getInstance()->getConnection();



      $stmt = $pdo->prepare("SELECT * FROM " . static::$tableName . " WHERE id = :id");
      $stmt->execute(['id' => $id]);

      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result ? static::maptoObject($result) : null;
  }

  public static  function FindAll(): ?static {
      $pdo = INITDB::class->getConnection();
      $stmt = $pdo->prepare("SELECT * FROM ". self::$tableName);
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return $result;
  }

    public static function Where(string $column, string $value, bool $option = false): ?static
    {


        $pdo = INITDB::getInstance()->getConnection();

        if ($option) {
            $query = "SELECT * FROM " . static::$tableName . " WHERE $column LIKE :value";
            $value = "%$value%";
        } else {
            $query = "SELECT * FROM " . static::$tableName . " WHERE $column = :value";
        }

        try {
            $stmt = $pdo->prepare($query);
            $stmt->execute(['value' => $value]);
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }


        $result = $stmt->fetch(PDO::FETCH_ASSOC);


        return $result ? static::mapToObject($result) : null;
    }


    public function save(): bool
    {
        $pdo = INITDB::getInstance()->getConnection();
        $fields = $this->toArray();
        $columns = array_keys($fields);

        if (isset($this->getId) && $this->getId> 0) {
            // update
            $setClause = implode(", ", array_map(fn($col) => "$col = :$col", $columns));
            $query = "UPDATE " . static::$tableName . " SET " . $setClause . " WHERE id = :id";
        } else {
            // insert
            $colNames = implode(", ", $columns);
            $placeholders = implode(", ", array_map(fn($col) => ":$col", $columns));
            $query = "INSERT INTO " . static::$tableName . " ($colNames) VALUES ($placeholders)";
        }

        try {
            $stmt = $pdo->prepare($query);
            $success = $stmt->execute($fields);
        } catch (\PDOException $e) {
            die("Query failed: " . $query);
        }

        // Gán id sau khi INSERT
        if ($success && (!isset($this->getId) || $this->getId== 0)) {
            $this->getId = $pdo->lastInsertId();
        }

        return $success;
    }


    public static function deleteById(int $id):bool  {
      try {
          $pdo = INITDB::getInstance()->getConnection();
          $query = "DELETE FROM ". self::$tableName . "WHERE id = :id";
          $stmt = $pdo->prepare($query);
          $stmt->execute(['id' => $id]);

          return $stmt;
      }catch (PDOException $e){
          throw $e;
      }

}

    public static function mapToObject(array $data)
    {
        $obj = new static();

        foreach ($data as $key => $value) {
            $method = 'set' . str_replace('_', '', ucwords($key, '_'));

            if (method_exists($obj, $method)) {
                // Nếu có setter thì gọi
                if (str_contains($key, 'date') || str_contains($key, 'create_at')) {
                    try {
                        $value = new \DateTime($value);
                    } catch (\Exception $e) {}
                }
                $obj->$method($value);
            }
        }

        return $obj;
    }






}