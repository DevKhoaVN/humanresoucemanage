<?php

namespace models;
require_once __DIR__ . "/BaseModel.php";

class Department extends BaseModel
{
    protected static string $tableName = 'department';

    private ?int $id = null;

    private string $name;

    private string $description;
    private ?bool $status = null;

    public function __construct()
    {

    }

    public function toArraySave() : array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status
        ];

    }
    public function position(): array{
        return Position::Where("department_Id" , $this->id);
    }

    //getter & setter
    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function isStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }



}