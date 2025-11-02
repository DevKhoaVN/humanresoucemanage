<?php

namespace services;

require_once __DIR__ ."/../models/Employee.php";
require_once __DIR__ ."/../config/INITDB.php";

use Cassandra\Date;
use config\INITDB;
use models\Employee;
use PDO;

class EmployeeService
{
    private PDO $pdo;

    public function __construct(){
        $this->pdo = INITDB::getInstance()->getConnection();
    }
   public function getAllEmployee(){
       return  Employee::FindAll();
   }

   public function getEmployeeById($id){
       return Employee::FindById($id);
   }

   public function createEmployee(Employee $employee){
       if($employee == null){
           throw new \Exception("Employee must not be null");
       }

       $result = $employee->save();

       return $result;
   }

   public function updateEmployee(Employee $employee){
       if($employee === null){
           throw new \Exception("Employee must not be null");
       }

       $employee->getCreateAt(null);


       $result = $employee->save()

       ;
       return $result;
   }

   public function deleteEmployee($id){
       $employee = Employee::FindById($id);
       if($employee === null){
           throw new \Exception("Employee must not be null");
       }

       $query = "UPDATE employee SET is_active = 0 WHERE id = :id";
       $stmt = $this->pdo->prepare($query);
       $stmt->execute(['id' => $id]);

       return $stmt->rowCount() > 0;

   }

   public function searchEmployee(string $searchTerm){

        if($searchTerm === null){
            throw new \Exception("Employee must not be null");
        }

        $result = Employee::Where("fullname", $searchTerm , true);

        return $result;
   }

}