<?php

namespace services;
require_once __DIR__ ."/../vendor/autoload.php";
require_once __DIR__ ."/../models/Account.php";
use models\Account;
use config\INITDB;
use models\Employee;
use models\Leaves;

class LeaveService
{
    public function getAllLeaves(){
        $leaves = Leaves::findAll();
        $result = [];
        foreach ($leaves as $leave) {
            $result[] = $leave->toArray();
        }

        return $result;

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