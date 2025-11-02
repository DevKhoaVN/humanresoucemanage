<?php

namespace services;
require_once __DIR__ ."/../vendor/autoload.php";
require_once __DIR__ ."/../models/Account.php";
use models\Account;
use config\INITDB;

class AccountService
{
    private Account  $account;
    private \PDO  $conn;

    public function __construct(){
        $this->account = new Account();
        $this->conn = INITDB::getInstance()->getConnection();
    }

    public function getAllAccounts(){
        return Account::findAll();
    }
    public function getAccount( int $id){
        if($id === null){
            throw new \Exception("Account id is null");
        }
        $result = Account::findById($id);

        if($result === null){
            throw new \Exception("Account id is null");
        }


        return $result;
    }

    public function updateAccount( Account $account){
        if($account === null){
            throw new \Exception("Account id is null");
        }
        $account->getHashPassword() ? password_hash($account->getHashPassword(), PASSWORD_BCRYPT) : null;
       $result =  $account->save();

        return $result ? true : false;
    }

//    public function deleteAccount( int $id){
//        if($id === null){
//            throw new \Exception("Account id is null");
//        }
//        $result = Account::deleteById($id,true);
//
//        return $result ? true : false;
//    }
//
//    public function eanbleRole( int $id){
//        if($id === null){
//            throw new \Exception("Account id is null");
//        }
//
//        try {
//            $query = "UPDATE account SET role = 'admin' WHERE id = :id";
//            $stmt = $this->conn->prepare($query);
//            $stmt->execute([":id" => $id]);
//        } catch (\PDOException $e) {
//            die("Query failed: " . $e->getMessage());
//        }
//
//        return $stmt->rowCount() > 0;
//    }
//
//    public function disableRole(int $id): bool
//    {
//        if ($id === null) {
//            throw new \Exception("Account id is null");
//        }
//
//        try {
//            $query = "UPDATE account SET role = 'employee' WHERE id = :id";
//            $stmt = $this->conn->prepare($query);
//            $stmt->execute([':id' => $id]);
//        } catch (\PDOException $e) {
//            die("Query failed: " . $e->getMessage());
//        }
//
//        return $stmt->rowCount() > 0;
//    }

}