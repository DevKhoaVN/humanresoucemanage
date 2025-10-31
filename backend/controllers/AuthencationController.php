<?php

use services\AuthencationService;
use Helper\RequestHelper;
class AuthencationController
{
    private  AuthencationService $authencationService;

    private function __construct()
    {
        $this->authencationService = new AuthencationService();
    }

  public function login(){

      header('Content-Type: application/json');

      try {

           $data = RequestHelper::getJsonBody();
           $email = $data['email'];
           $password = $data['password'];

          $response = $this->authencationService->login($email, $password);
          http_response_code(200);
          echo json_encode($response);

      }catch (InvalidArgumentException $e){
          http_response_code(400);
          echo json_encode([
              'code' => 400,
              'status' => 'error',
              'message' => $e->getMessage()
          ]);
      }catch (Exception $e){

          http_response_code(500);
          echo json_encode([
              'code' => 500,
              'status' => 'error',
              'message' => 'Authencation  service error'
          ]);
      }

  }

  public function Register()
  {

  }
  public function logout()
  {

  }


}