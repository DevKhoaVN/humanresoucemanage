<?php

namespace models;

use Cassandra\Date;

require_once __DIR__ . "/BaseModel.php";
class Employee extends BaseModel
{
    protected static string $tableName = 'employee';
    private ?int $id = null;
    private  $fullname;
    private  $phone;

    private  $email;
    private  $address;

   private \DateTime $create_at;
   private  $position_id;

    public function __construct()
    {
        $this->create_at = new \DateTime();
    }


    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'fullname'=> $this->getFullname(),
            'address' => $this->getAddress(),
            'email' => $this->getEmail(),
            "phone" => $this->getPhone(),
            'position_id' => $this->getPositionId(),
        ];
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

    public function getIsActive(): bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): void
    {
        $this->is_active = $is_active;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getFullname(): string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): void
    {
        $this->fullname = $fullname;
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
