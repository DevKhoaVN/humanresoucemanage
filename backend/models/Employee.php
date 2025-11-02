<?php

namespace models;

use Cassandra\Date;

require_once __DIR__ . "/BaseModel.php";
class Employee extends BaseModel
{
    protected static string $tableName = 'employee';
    private int $id;
    private string $full_name;
    private int $phone;


    private string $email;
    private string $address;

   private \DateTime $create_at;
   private int $position_id;

    public function __construct(int $id, string $full_name, int $phone, string $email, string $address, \DateTime $create_at, int $position_id)
    {
        $this->id = $id;
        $this->full_name = $full_name;
        $this->phone = $phone;
        $this->email = $email;
        $this->address = $address;
        $this->create_at = $create_at;
        $this->position_id = $position_id;
    }

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

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getCreateAt(): \DateTime
    {
        return $this->create_at;
    }

    public function setCreateAt(\DateTime $create_at): void
    {
        $this->create_at = $create_at;
    }

    public function getPositionId(): int
    {
        return $this->position_id;
    }

    public function setPositionId(int $position_id): void
    {
        $this->position_id = $position_id;
    }


}
