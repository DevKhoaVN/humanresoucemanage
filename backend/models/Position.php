<?php

namespace models;

require_once __DIR__ .'/BaseModel.php';
require_once __DIR__ .'/Department.php';

class Position extends BaseModel
{
    protected static string $tableName = "positions";

    private ?int $id = null;
    private  int $department_id ;
    private string $name;

    private float $base_salary;
    private string $description;


    public function __construct()
    {

    }
    public function toArray()
    {
        $department = $this->department();

        return [
            "id" => $this->id,
            "department_id" => $this->department_id,
            "department_name" =>  $department ? $department->getName() : "",
            "name" => $this->name,
            "salary" => $this->base_salary,
            "description" => $this->description,

        ];

    }


    public function toArraySave()
    {
        return [
            'id' => $this->id,
            'department_id' => $this->department_id,
            'name' => $this->name,
            'base_salary' => $this->base_salary,
            'description' => $this->description,

        ];

    }

    public function department(): ?Department{
        return Department::mapToObject(Department::findById($this->department_id));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getDepartmentId(): int
    {
        return $this->department_id;
    }

    public function setDepartmentId(int $department_id): void
    {
        $this->department_id = $department_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getBaseSalary(): float
    {
        return $this->base_salary;
    }

    public function setBaseSalary(float $base_salary): void
    {
        $this->base_salary = $base_salary;
    }



    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    //getter & setter


}