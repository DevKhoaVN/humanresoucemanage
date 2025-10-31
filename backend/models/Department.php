<?php

namespace models;

class Department extends BaseModel
{
    protected static string $table = 'department';

    private int $id;

    private string $name;

    private bool $active;

    public function __construct(?int $id,bool $active, string $name)
    {
        $this->active = $active;
        $this->name = $name;
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }



}