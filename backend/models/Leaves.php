<?php

namespace models;

class Leaves extends BaseModel
{
 protected  static string $table = 'leaves';

 private int $id;
 private int $employee_id;
 private string $start_date;
 private string $end_date;
 private string $leave_type;
 private string $reason;


    /**
     * @param string $start_date
     * @param string $end_date
     * @param string $leave_type
     * @param string $reason
     */
    public function __construct(string $start_date, string $end_date, string $leave_type, string $reason)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->leave_type = $leave_type;
        $this->reason = $reason;
    }

  // 1:1 Employee table
    public function employee() : ?Employee{
        return Employee::findById($this->employee_id);
    }


    //getter setter
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
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

    public function getEndDate(): string
    {
        return $this->end_date;
    }

    public function setEndDate(string $end_date): void
    {
        $this->end_date = $end_date;
    }

    public function getLeaveType(): string
    {
        return $this->leave_type;
    }

    public function setLeaveType(string $leave_type): void
    {
        $this->leave_type = $leave_type;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }


}