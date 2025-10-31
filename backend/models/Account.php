<?php

namespace models;

class Account extends BaseModel
{
    protected static string $tableName = 'account';
    private int  $id;
    private $username;
    private $hash_password;
    private $email;
    private $role;

    private $status;

    /**
     * @param int $id
     * @param $username
     * @param $hash_password
     * @param $email
     * @param $role
     * @param $status
     */
    public function __construct(?int $id, $username, $hash_password, $email, $role, $status)
    {
        $this->id = $id;
        $this->username = $username;
        $this->hash_password = $hash_password;
        $this->email = $email;
        $this->role = $role;
        $this->status = $status;
    }

    public static  function mapToObject(array $array): Account{
        $account = new Account();
        $account->setEmail($array['email']);
        $account->setUsername($array['username']);
        $account->setHashPassword($array['hash_password']);
        $account->setRole($array['role']);
        $account->setStatus($array['status']);
        return $account;
    }


    public function employee(): ?Employee{
        return Employee::findById($this->id);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUsername(): mixed
    {
        return $this->username;
    }

    public function setUsername(mixed $username): void
    {
        $this->username = $username;
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

    public function getEmail(): mixed
    {
        return $this->email;
    }

    public function setEmail(mixed $email): void
    {
        $this->email = $email;
    }

    public function getRole(): mixed
    {
        return $this->role;
    }

    public function setRole(mixed $role): void
    {
        $this->role = $role;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }



}
