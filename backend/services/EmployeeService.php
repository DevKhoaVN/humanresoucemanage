<?php

namespace services;
use exception\InvalidArgumentException;
use models\Account;
use models\Employee;
use PDOException;
use repository\EmployeeRepository;

class EmployeeService
{

    public function insertEPL( Employee $employee ) : void{

       if(!$employee instanceof Employee && $employee == null ){

           throw new InvalidArgumentException("Employee should be an instance of Employee");
       }

       try{
           $employee = new Employee();
           $employee->setFullName($employee->getFullName());
           $employee->setEmail($employee->getEmail());
           $employee->setPhone($employee->getPhone());
           $employee->attendance()->

           $result = $employee->save();
       }catch(PDOException $e){
           echo $e->getMessage();
       }
    }

    public function getAllEmployees(){
        try {
            return Employee::FindAll();
        }catch (PDOException $e){
            echo $e->getMessage();
        }

    }
    public function getEmployeesById( int $id)
    {
        try {
            return Employee::FindById($id);
        }catch (PDOException $e){
            echo $e->getMessage();
        }

    }
    public function updateAccount(Account $account){

        if(!$account instanceof Account || $account == null ){
            throw new InvalidArgumentException("Account should be an instance of Account");
        }

        try {
            return $account->save();
        }catch (PDOException $e){
            echo $e->getMessage();
        }
    }

    public function deleteAccount(Account $account){
        if(!$account instanceof Account || $account == null ){
            throw new InvalidArgumentException("Account should be an instance of Account");
        }

        try {
            return $account::deleteById($account->getId());
        }catch (PDOException $e){
            echo $e->getMessage();
        }
    }

    public function  searchEmployee(string $searchtemp): array
    {
        if($searchtemp == null || $searchtemp == ""){
            throw new InvalidArgumentException("serchtemp should be an instance of Employee");
        }


        try {

            $result = Employee::Where('fullName', $searchtemp , true);
            return $result;
        }catch (PDOException $e){
            echo "have an error in search employee: ". $e->getMessage();
        }

    }



}