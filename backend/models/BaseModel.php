<?php

namespace models;
require_once __DIR__ . '/../config/INITDB.php';

use config\INITDB;
use PDO;
use PDOException;

Abstract class BaseModel
{
  protected static string $tableName = '';

  public static function findById(int $id): array
  {
      $pdo = INITDB::getInstance()->getConnection();



      $stmt = $pdo->prepare("SELECT * FROM " . static::$tableName . " WHERE id = :id  ORDER BY id DESC");
      $stmt->execute(['id' => $id]);

      $result = $stmt->fetch(PDO::FETCH_ASSOC);


      return $result ;
  }

    public static function FindAll(): array
    {
        try {
            $pdo = INITDB::getInstance()->getConnection();
            $stmt = $pdo->prepare("SELECT * FROM " . static::$tableName);
            $stmt->execute();

            // ánh xạ sang class gọi hàm
            $stmt->setFetchMode(PDO::FETCH_CLASS, static::class);

            $result = $stmt->fetchAll();

            return $result;
        } catch (\PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }


    public static function Where(string $column, string $value, bool $option = false): array
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


         $option ?$result = $stmt->fetchAll(PDO::FETCH_ASSOC) :  $result = $stmt->fetch(PDO::FETCH_ASSOC);


        return $result;
    }


    public function save(): bool
    {
        $pdo = INITDB::getInstance()->getConnection();
        $fields = $this->toArraySave();


        // loc bo filed null
        $fields = array_filter($fields , fn($value) => $value !== null);
        $columns = array_keys($fields);

        if ($this->getId() !== null)  {
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
        if ($success && empty($this->getId())) {
            $this->setId($pdo->lastInsertId());
        }

        return $stmt->rowCount() > 0;
    }

    public static function deleteById(int $id , $deleteSoft = false):bool  {
      try {
          $pdo = INITDB::getInstance()->getConnection();

          if($deleteSoft){
              $query = "UPDATE" . static::$tableName . " SET is_active = 0 WHERE id = :id";
          }else{
              $query = "DELETE FROM " . static::$tableName . " WHERE id = :id";
          }
          $stmt = $pdo->prepare($query);
          $stmt->execute(['id' => $id]);

          return $stmt->rowCount() > 0;
      }catch (PDOException $e){
          die("Query failed: " . $e->getMessage());
      }
}

    public static function mapToObject(array $data)
    {
        $obj = new static();

        foreach ($data as $key => $value) {
            $method = 'set' . str_replace('_', '', ucwords($key, '_'));

            if (method_exists($obj, $method)) {
                // Nếu có setter thì gọi
                if (str_contains($key, 'dates') || str_contains($key, 'create_at')) {
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