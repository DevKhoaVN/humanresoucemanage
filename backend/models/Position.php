<?php

namespace models;

class Position extends BaseModel
{
    protected static string $tableName = "position";

    private int $id;
    private string $name;

    private float $salary;
    private string $description;

    /**
     * @param int $id
     * @param string $name
     * @param float $salary
     * @param string $description
     */
    public function __construct(?int $id, string $name, float $salary, string $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->salary = $salary;
        $this->description = $description;
    }

    public function departmentId(): Department{
        return Department::findById($this->id);
    }

    //getter & setter
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSalary(): float
    {
        return $this->salary;
    }

    public function setSalary(float $salary): void
    {
        $this->salary = $salary;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }



}