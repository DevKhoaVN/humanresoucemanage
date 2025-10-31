<?php

namespace models;

use DateTime;

class Review extends BaseModel
{

    protected static string $table = 'review';

    private int $employee_id;
    private string $moth;

    private string $content;

    private \DateTime $created_at;

    private DateTime $updated_at;


    public function __construct(int $employee_id, string $moth, string $content, DateTime $created_at, DateTime $updated_at)
    {
        $this->employee_id = $employee_id;
        $this->moth = $moth;
        $this->content = $content;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    // relation

    public function review():Employee{
        return Employee::findById($this->employee_id);
    }


    // getter & setter
    public function getEmployeeId(): int
    {
        return $this->employee_id;
    }

    public function setEmployeeId(int $employee_id): void
    {
        $this->employee_id = $employee_id;
    }

    public function getMoth(): string
    {
        return $this->moth;
    }

    public function setMoth(string $moth): void
    {
        $this->moth = $moth;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(DateTime $updated_at): void
    {
        $this->updated_at = $updated_at;
    }



}