<?php

namespace models;


use DateTime;

require_once __DIR__ . "/BaseModel.php";
require_once __DIR__ . "/Employee.php";
class Account extends BaseModel
{
    protected static string $tableName = 'account';
    private ?int  $id =null;
    private $username;
    private $passwordhash;
    private $role;
    private $is_active;
    private ?string $create_at = null;

    public function __construct()
    {
    }



    public function toArraySave(): array
    {
        return [
            "id" => $this->getId(),
            'username' => $this->getUsername(),
            'passwordhash' => $this->getPasswordhash(),
            'role' => $this->getRole(),
            'is_active' => $this->getIsActive(),
            'create_at' => $this->getCreateAt()
                ? new DateTime($this->getCreateAt())
                : null,

        ];
    }

    public function getCreateAt(): ?string
    {
        return $this->create_at;
    }

    public function setCreateAt(string $create_at): void
    {
        $this->create_at = $create_at;
    }


    public function employee(): ?Employee{
        return Employee::mapToObject(Employee::Where("postion_id" , $this->getId()));
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * @param mixed $is_active
     */
    public function setIsActive($is_active): void
    {
        $this->is_active = $is_active;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role): void
    {
        $this->role = $role;
    }

    /**
     * @return mixed
     */
    public function getPasswordhash()
    {
        return $this->passwordhash;
    }

    /**
     * @param mixed $passwordhash
     */
    public function setPasswordhash($passwordhash): void
    {
        $this->passwordhash = $passwordhash;
    }



    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }


}
