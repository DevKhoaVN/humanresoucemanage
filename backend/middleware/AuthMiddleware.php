<?php
namespace middleware;

require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use models\Employee;

require_once __DIR__ . "/../models/Employee.php";

class AuthMiddleware
{

    public static function handle()
    {

        if (empty($_COOKIE['auth_token'])) {
            self::unauthorized("Missing token");
        }



        $token = trim($_COOKIE['auth_token']);

        try {
            // Decode token
            $payload = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));

            if (!isset($payload->exp) || $payload->exp < time()) {
                self::unauthorized("Token expired");
            }


            // ✅ Check user id
            $id = $payload->id ?? null;
            if (!$id) {
                self::unauthorized("Invalid token payload");
            }

            // ✅ Check account existence
            $isAccount = Employee::findById($id);
            if (!$isAccount) {
                self::unauthorized("Account not found");
            }

            // ✅ If everything OK
            return true;

        } catch (\Exception $e) {
            self::unauthorized("Invalid token: " . $e->getMessage());
        }
    }

    private static function unauthorized(string $msg)
    {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['code' => 401 , 'error' => 'Unauthorized', 'message' => $msg]);
        exit;
    }
}
