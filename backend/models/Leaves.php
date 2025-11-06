<?php

namespace models;
require_once __DIR__ . "/BaseModel.php";
require_once __DIR__ .'/Employee.php';


use DateTime;

class Leaves extends BaseModel
{
 protected  static string $tableName = 'leaves';

 private ?int $id = null;
 private int $employee_id;
 private ?string $leave_type = null;
 private ?string $reason = null ;
 private int $status;
 private ?string $created_at = null;
 private ?string $leave_date = null;


    /**
     * @param string $start_date
     * @param string $end_date
     * @param string $leave_type
     * @param string $reason
     */
    public function __construct()
    {

    }

  // 1:1 Employee table
    public function employee() : ?Employee{
        return Employee::mapToObject(Employee::findById($this->employee_id));
    }


    public function  toArraySave()
    {

        return [
            "id" => $this->id,
            "employee_id" => $this->employee_id,
            "leave_type" => $this->leave_type,
            "reason" => $this->reason,
            "status" => $this->status,
            "created_at" => $this->created_at ? new DateTime($this->created_at) : null,
            "leave_date" => $this->leave_date
        ];

    }
    public function  toArray()
    {
        $employee = $this->employee();

        return [
            "id" => $this->id,
            "employee_id" => $this->employee_id,
            "employee _ name" => $employee ? $employee->getFullName() : null,
            "leave_type" => $this->leave_type,
            "reason" => $this->reason,
            "status" => $this->status,
            "created_at" => $this->created_at ? new DateTime($this->created_at) : null,
            "leave_date" => $this->leave_date
        ];

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

    public function getStartDate(): string
    {
        return $this->start_date;
    }

    public function setStartDate(string $start_date): void
    {
        $this->start_date = $start_date;
    }

    public function getLeaveType(): ?string
    {
        return $this->leave_type;
    }

    public function setLeaveType(?string $leave_type): void
    {
        $this->leave_type = $leave_type;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): void
    {
        $this->reason = $reason;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function setCreatedAt(?string $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getLeaveDate(): ?string
    {
        return $this->leave_date;
    }

    public function setLeaveDate(?string $leave_date): void
    {
        $this->leave_date = $leave_date;
    }


}