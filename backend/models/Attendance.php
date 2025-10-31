<?php

namespace models;
use DateTime;
class Attendance extends BaseModel
{
  private int $id;
  private int $employeeId;

  private DateTime $work_date;

  private DateTime $check_in;
  private DateTime $check_out;

    /**
     * @param int $id
     * @param int $employeeId
     * @param DateTime $work_date
     * @param DateTime $check_in
     * @param DateTime $check_out
     */
    public function __construct(?int $id, int $employeeId, DateTime $work_date, DateTime $check_in, DateTime $check_out)
    {
        $this->id = $id;
        $this->employeeId = $employeeId;
        $this->work_date = $work_date;
        $this->check_in = $check_in;
        $this->check_out = $check_out;
    }

    public function employee(): ?Employee{
        return Employee::findById($this->employeeId);
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

    public function getEmployeeId(): int
    {
        return $this->employeeId;
    }

    public function setEmployeeId(int $employeeId): void
    {
        $this->employeeId = $employeeId;
    }

    public function getWorkDate(): DateTime
    {
        return $this->work_date;
    }

    public function setWorkDate(DateTime $work_date): void
    {
        $this->work_date = $work_date;
    }

    public function getCheckIn(): DateTime
    {
        return $this->check_in;
    }

    public function setCheckIn(DateTime $check_in): void
    {
        $this->check_in = $check_in;
    }

    public function getCheckOut(): DateTime
    {
        return $this->check_out;
    }

    public function setCheckOut(DateTime $check_out): void
    {
        $this->check_out = $check_out;
    }

}