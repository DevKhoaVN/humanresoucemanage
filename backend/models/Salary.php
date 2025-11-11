<?php

namespace models;

require_once __DIR__ .'/BaseModel.php';
require_once __DIR__.'/Employee.php';

use DateTime;

class Salary extends  BaseModel
{

    protected static string $tableName = 'salary';
    private ?int  $id = null;
    private int $employee_id ;

    private string $absent_days;
    private string $work_days;
    private string $base_salary ;
    private string $total_salary;

    private  string  $created_at;

    public function toArray()
    {
        $employee = $this->employee();

        return [
            'id' => $this->id,
            'employee_id' => (int)$this->employee_id,
            'employee_name' => $employee ? $employee->getFullName() : '',
            'position' => $employee ? $employee->position()->getName() : '',

            'absent_days' => (int)$this->absent_days,
            'work_days' => (int)$this->work_days,


            'base_salary' => round((float)$this->base_salary, 3),
            'total_salary' => round((float)$this->total_salary, 3),

            'created_at' => $this->created_at
                ? date('Y-m-d H:i:s', strtotime($this->created_at))
                : null,
        ];
    }


    public function toArraySave()
    {

        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'absent_day' => $this->absent_days,
            'work_days' => $this->work_days,
            'base_salary' => $this->base_salary,
            'total_salary' => $this->total_salary,


        ];

    }

    public function employee(): ?Employee
    {
        return Employee::mapToObject(Employee::findById($this->employee_id));
    }

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

    public function getAbsentDays(): int
    {
        return $this->absent_days;
    }

    public function setAbsentDays(int $absent_days): void
    {
        $this->absent_days = $absent_days;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }



    public function getWorkDays(): int
    {
        return $this->work_days;
    }

    public function setWorkDays(int $work_days): void
    {
        $this->work_days = $work_days;
    }

    public function getBaseSalary(): float
    {
        return $this->base_salary;
    }

    public function setBaseSalary(float $base_salary): void
    {
        $this->base_salary = $base_salary;
    }

    public function getTotalSalary(): float
    {
        return $this->total_salary;
    }

    public function setTotalSalary(float $total_salary): void
    {
        $this->total_salary = $total_salary;
    }

}