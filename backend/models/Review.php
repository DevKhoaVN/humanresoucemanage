<?php

namespace models;

require_once __DIR__ . "/Employee.php";

use DateTime;

class Review extends BaseModel
{

    protected static string $tableName = 'review';

    private ?int $id = null;
    private int $employee_id;

    private string $content;

    private ?string $created_at = null;



    public function __construct()
    {

    }

    public function toArraySave(){
        return [
            'id' => $this->id,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'employee_id' => $this->employee_id,
        ];
    }

    public function toArray(){

        $employee = $this->employee();

        return [
            'id' => $this->id,
            'content' => $this->content,
            'created_at' => $this->created_at ? new DateTime($this->created_at) : null,
            'employee_id' => $this->employee_id,
            'employee_name'=> $employee ? $employee->getFullName() : null,
        ];
    }

    // relation

    public function employee():?Employee{
        return Employee::mapToObject(Employee::findById($this->employee_id));
    }


    //getter & setter
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getEmployeeId(): int
    {
        return $this->employee_id;
    }

    public function setEmployeeId(int $employee_id): void
    {
        $this->employee_id = $employee_id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function setCreatedAt(?string $created_at): void
    {
        $this->created_at = $created_at;
    }



}