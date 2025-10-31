<?php

namespace services;

use exception\InvalidArgumentException;
use exception\NotFoundException;
use models\Account;
use FireBase\JWT\JWT;
use Firebase\JWT\Key;

class AuthencationService
{

    public function login(string $email, string $password): array
    {
        if (empty($email) || empty($password)) {
            throw new InvalidArgumentException("Email and password are required!");
        }

        $account_exist = Account::Where("email", $email, true);
        if ($account_exist == null) {
            throw new InvalidArgumentException("Account with this email does not exist!");
        }

        $account = Account::mapToObject($account_exist);

        if (!password_verify($password, $account->getHashPassword())) {
            throw new InvalidArgumentException("Wrong password!");
        }

        $secret = $_ENV['JWT_SECRET'] ?? 'default_secret';
        $expire_time = time() + 3600;

        $payload = [
            'sub' => $account->getId(),
            'email' => $account->getEmail(),
            'role' => $account->getRole(),
            'iat' => time(),
            'exp' => $expire_time
        ];

        $token = JWT::encode($payload, $secret, 'HS256');

        // xử lý cookie
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
                'id' => $account->getId(),
                'name' => $account->getUsername(),
                'email' => $account->getEmail(),
                'role' => $account->getRole(),
            ],
            'token_expired_at' => date('Y-m-d H:i:s', $expire_time)
        ];
    }

    public function register(string $name, string $email, string $password)
    {

            if ($this->is_valid([$name, $email, $password])) {
                throw new InvalidArgumentException("The name, email, or password is invalid!");
            }


            $is_account = Account::Where("email", $email, true);

            if ($is_account != null) {
                throw new InvalidArgumentException("The account already exists. Please login!");
            }

            $account = new Account();
            $account->setUsername($name);
            $account->setEmail($email);
            $account->setHashPassword(password_hash($password, PASSWORD_BCRYPT));
            $account->save();

            return [
                'code' => 201,
                'status' => 'success',
                'message' => 'Account created successfully!'
            ];

    }


    public function logout()
    {

            $token = $_COOKIE["auth_token"] ?? null;

            if(!$token){
                throw new \InvalidArgumentException("Not token found in cookie");
            }

           setcookie("auth_token", "", time() - 3600,'/', '', false, true);

            return [
                'code' => 200,
                'status' => 'success',
                'message' => 'Logout successful!'
            ];
    }



    private function is_valid(array $array): bool
    {
        foreach ($array as $value) {
            if ($value == null) return false;

        }

        return true;
    }
}

