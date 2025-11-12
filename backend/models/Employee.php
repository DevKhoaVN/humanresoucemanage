<?php

namespace models;



require_once __DIR__ . "/BaseModel.php";
require_once __DIR__ . "/Position.php";
class Employee extends BaseModel
{
    protected static string $tableName = 'employee';
    private ?int $id = null;
    private  $fullname;
    private  $phone;

    private  $email;
    private  $address;
    private  $is_active;
   private ?string $created_at = null;
   private  $position_id;

    public function __construct()
    {

    }


    public function toArray(): array
    {
        $positon = $this->position();
        return [
            'id' => $this->getId(),
            'fullname'=> $this->getFullname(),
            'address' => $this->getAddress(),
            'email' => $this->getEmail(),
            "phone" => $this->getPhone(),
            'position_id' => $this->getPositionId(),
            "department_id" => $positon->department()->getId(),
            "department_name" => $positon->department()->getName(),
            'create_at' => $this->getCreatedAt() ? new \DateTime($this->getCreatedAt()) : null,
            "is_active" => $this->getIsActive(),
            'position_name' => $positon ? $positon->getName() : null,
        ];
    }

    public function toArraySave(): array
    {
        $positon = $this->position();
        return [
            'id' => $this->getId(),
            'fullname'=> $this->getFullname(),
            'address' => $this->getAddress(),
            'email' => $this->getEmail(),
            "phone" => $this->getPhone(),
            'position_id' => $this->getPositionId(),
            'create_at' => $this->getCreatedAt(),
            "is_active" => $this->getIsActive(),
        ];
    }
    public function position() : ?Position{
        return Position::mapToObject(Position::findById($this->getPositionId()));

    }

    public function attendance() : array{
        return Attendance::Where("employee_id",$this->id);
    }

    public function leaves():array
    {
        return Leaves::Where("employee_id",$this->id);
    }

    public function getIsActive(): ?bool
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

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function setCreatedAt(?string $created_at): void
    {
        $this->created_at = $created_at;
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
