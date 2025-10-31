<?php

namespace models;

class Employee extends BaseModel
{
    protected static string $tableName = 'employee';
    private int $id;
    private string $full_name;
    private int $phone;
    private string $email;

   private bool $active;

   private int $position_id;

    public function __construct(){}


    public function position() : ?Position{
        return Position::findById($this->position_id);

    }

    public function attendance() : array{
        return Attendance::Where("employee_id",$this->id);
    }

    public function leaves():array
    {
        return Leaves::Where("employee_id",$this->id);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getFullName(): string
    {
        return $this->full_name;
    }

    public function setFullName(string $full_name): void
    {
        $this->full_name = $full_name;
    }

    public function getPhone(): int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): void
    {
        $this->phone = $phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }




}
