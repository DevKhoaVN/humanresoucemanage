<?php

namespace models;

use DateTime;

require_once __DIR__ . "/BaseModel.php";
class Account extends BaseModel
{
    protected static string $tableName = 'account';
    private int  $id;
    private $username;
    private $hash_password;
    private $role;
    private $is_active;
    private ?DateTime $create_at;

    /**
     * @param DateTime $create_at
     * @param $is_active
     * @param $role
     * @param $hash_password
     * @param $username
     * @param int $id
     */
//    public function __construct(DateTime $create_at, $is_active, $role, $hash_password, $username, int $id)
//    {
//        $this->create_date = $create_at;
//        $this->is_active = $is_active;
//        $this->role = $role;
//        $this->hash_password = $hash_password;
//        $this->username = $username;
//        $this->id = $id;
//    }
    public function __construct()
    {
        $this->create_at = new \DateTime(); // mặc định là thời điểm hiện tại
    }



    public function toArray(): array
    {
        return [
            "id" => $this->getId(),
            'username' => $this->getUsername(),
            'passwordhash' => $this->getHashPassword(),
            'role' => $this->getRole(),
            'is_active' => $this->getIsActive(),
            'create_at' => $this->getCreateAt() instanceof \DateTime
                ? $this->getCreateAt()->format('Y-m-d')
                : $this->getCreateAt()
        ];
    }

    public function getCreateAt(): ?DateTime
    {
        return $this->create_at;
    }

    public function setCreateAt(?DateTime $create_at): void
    {
        $this->create_at = $create_at;
    }


    public function employee(): ?Employee{
        return Employee::findById($this->id);
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
    public function getHashPassword()
    {
        return $this->hash_password;
    }

    /**
     * @param mixed $hash_password
     */
    public function setHashPassword($hash_password): void
    {
        $this->hash_password = $hash_password;
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

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }


}
