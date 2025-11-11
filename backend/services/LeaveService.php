<?php

namespace services;
require_once __DIR__ ."/../vendor/autoload.php";
require_once __DIR__ ."/../models/Account.php";
require_once __DIR__ ."/../config/INITDB.php";
use models\Account;
use config\INITDB;
use models\Employee;
use models\Leaves;

class LeaveService
{
    private \PDO $pdo;

    public function __construct(){
        $this->pdo = INITDB::getInstance()->getConnection();
    }
    public function getAllLeaves( $date){

        try {
            $query = "SELECT * FROM leaves WHERE DATE(leave_date) = :date";
            $stmt = $this->pdo->prepare($query);
            $stmt->setFetchMode(\PDO::FETCH_CLASS, Leaves::class);
            $stmt->execute(["date" => $date]);
            $leaves = $stmt->fetchAll();
            $result = [];
            foreach ($leaves as $leave) {
                $result[] = $leave->toArray();
            }
            return $result;
        } catch (\Exception $e) {

            throw new \Exception("Lỗi khi lấy dữ liệu leaves: " . $e->getMessage());
        }

    }
    public function updateLeaves( Leaves $leave)
    {
        if ($leave === null) {
            throw new \Exception("Employee id is null");
        }

        $result = $leave->save();

        return $result ? true : false;
    }


    public function deleteAccount( int $id){
        if($id === null){
            throw new \Exception("Employee id is null");
        }
        $result = Leaves::deleteById($id);

        return $result;
    }

}