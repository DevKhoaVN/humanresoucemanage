<?php

namespace models;

require_once __DIR__ .'/BaseModel.php';
require_once __DIR__ .'/Employee.php';
use DateTime;
class Attendance extends BaseModel
{
    protected  static  string $tableName = 'attendance';
    private int $id;
    private string $work_date ;
    private int $employee_id ;
    private ?string $check_in = null;
    private ?string $check_out = null;
    private ?string $note = null;



    public function __construct(){}


    public function toArray(): array{
        $employee = $this->employee();

        return [
            'id' => $this->id,
            'employee_id' => $employee->getId() || null ,
            'employee_name' => $employee ? $employee->getFullname() : '',
            'work_date' => $this->work_date,

            'check_in' => $this->check_in,
            'check_out' => $this->check_out,
            'note' => $this->note,
        ];
    }

    public function toArraySave(): array{
        $employee = $this->employee();

        return [
            'id' => $this->id,
            'employee_id' => $employee->getId() || null ,
            'work_date' => $this->work_date ,
            'check_in' => $this->check_in,
            'check_out' => $this->check_out,
            'note' => $this->note,
        ];
    }

    private function employee(): ?Employee{
        return Employee::mapToObject(Employee::findById($this->employee_id));
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getWorkDate(): string
    {
        return $this->work_date;
    }

    public function setWorkDate(string $work_date): void
    {
        $this->work_date = $work_date;
    }

    public function getEmployeeId(): int
    {
        return $this->employee_id;
    }

    public function setEmployeeId(int $employee_id): void
    {
        $this->employee_id = $employee_id;
    }

    public function getCheckIn(): ?string
    {
        return $this->check_in;
    }

    public function setCheckIn(?string $check_in): void
    {
        $this->check_in = $check_in;
    }

    public function getCheckOut(): ?string
    {
        return $this->check_out;
    }

    public function setCheckOut(?string $check_out): void
    {
        $this->check_out = $check_out;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): void
    {
        $this->note = $note;
    }


}