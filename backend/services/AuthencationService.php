<?php

namespace services;

require_once __DIR__ . "/../models/Account.php";
require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use models\Account;

class AuthencationService
{

    public function login(string $username, string $password)
    {


        if (empty($username) || empty($password)) {
            throw new \Exception("username and password are required!");
        }

        $account = Account::Where("username", $username,false);


        if ($account == null) {
            throw new \Exception("Account with this email does not exist!");
        }

        if (!password_verify($password, $account["passwordhash"])){
               throw new \Exception("Wrong password!");
        }


        $expire_time = time() + 3600;

        $payload = [
            'id' => $account["id"],
            'username' => $account["username"],
            'role' => $account["role"],
            'iat' => time(),
            'exp' => $expire_time
        ];

        $token = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

        setcookie("auth_token", $token, [
            'expires' => $expire_time,
            'path' => '/',
            'secure' => false,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);


        return [
            'code' => 200,
            'status' => 'success',
            'message' => 'Login successfully!',
            'account' => [
                'id' => $account["id"],
                'username' => $account["username"],
                'role' => $account["role"],
            ],
            'token_expired_at' => date('Y-m-d H:i:s', $expire_time)
        ];
    }

    public function register(string $username, string $password)
    {

        if ($username== null ||$password== null) {
            throw new \Exception("The name, email, or password is invalid!");
        }

        $is_account = Account::Where("username",$username, true);

        if ($is_account != null) {
            throw new \Exception("The account already exists. Please login!");
        }



        $account = new Account();
        $account->setUsername($username);
        $account->setPasswordhash(password_hash($password, PASSWORD_BCRYPT));
        $account->setRole("employee");
        $account->setIsActive(true);

        $account->save();

        return [
            'code' => 201,
            'status' => 'success',
            'message' => 'Account created successfully!',
            'username' => $account->getUsername(),
        ];

    }


    public function logout()
    {
        if (isset($_COOKIE["auth_token"])) {
            setcookie("auth_token", "", [
                'expires'  => time() - 3600,
                'path'     => '/',
                'secure'   => false,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
        }

        return true;
    }


}



