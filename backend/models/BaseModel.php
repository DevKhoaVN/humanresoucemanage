<?php

namespace models;
require_once __DIR__ . '/../config/INITDB.php';

use config\INITDB;
use PDO;
use PDOException;

Abstract class BaseModel
{
  protected static string $tableName;

  public static function findById(int $id): ?static
  {
      $pdo = INITDB::class->getConnection();

      $stmt = $pdo->prepare("SELECT * FROM " . static::$tableName . " WHERE id = :id");
      $stmt->execute(['id' => $id]);

      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result ? static::maptoObject($result) : null;
  }

  pubic static  function FindAll(): ?static {
      $pdo = INITDB::class->getConnection();
      $stmt = $pdo->prepare("SELECT * FROM ". self::$tableName);
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return $result;
  }

  public  static function Where (string $colum , string $value , bool $option = false): ?self{

      $pdo = INITDB::getInstance()->getConnection();

      if($option){
          $query = "SELECT * FROM " . self::$tableName . " WHERE $colum LIKE :value";
          $value = "%$value%";
      }else{
          $query = "SELECT * FROM " . self::$tableName . " WHERE $colum = :value";
      }

      $stmt = $pdo->prepare($query);

      $stmt->execute([
          'value' => $value
      ]);

      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result ? static::maptoObject($result) : [];
  }

  public function save() : bool
  {
      $pdo = INITDB::class->getConnection();
      $fields = get_object_vars($this);
      $colum = array_keys($fields);

      // check if id exist : action - update <> action - insert
      if (isset($this->id)) {
          $query = "UPDATE" . self::$tableName . " SET" . implode(",", array_map(fn($col) => "$col = :$col", $colum));
      } else {
          $query = "INSERT INTO" . self::$tableName ."VALUES". implode(",", array_map(fn($col) => "$col = :$col", $colum));
      }

      $stmt = $pdo->prepare($query);
      $success = $stmt->execute($fields);

      // Gán id sau khi insert
      if ($success && !isset($this->id)) {
          $this->id = $pdo->lastInsertId();
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

  private static function maptoObject(array $array){
      $object = new static();
      foreach($array as $key => $value){
          $object->$key = $value;
      }

      return $object;
  }




}