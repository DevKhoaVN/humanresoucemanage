<?php

namespace services;
require_once __DIR__ ."/../config/INITDB.php";
require_once __DIR__ ."/../models/Department.php";

use config\INITDB;
use models\Department;


class DepartmentService
{
    private Department  $department;
    private \PDO  $conn;

    public function __construct(){
        $this->department = new department();
        $this->conn = INITDB::getInstance()->getConnection();
    }

    public function getAllDepartments(){
        $leaves = Department::findAll();
        $result = [];
        foreach ($leaves as $leave) {
            $result[] = $leave->toArraySave();
        }

        return $result;

    }
    public function updateDepartment( Department $department)
    {
        if ($department === null) {
            throw new \Exception("department must not null");
        }

        $result = $department->save();

        return $result ? true : false;
    }


    public function deleteDepartment( int $id){
        if($id === null){
            throw new \Exception("Department id must not null");
        }
        $result = Department::deleteById($id);

        return $result;
    }



}