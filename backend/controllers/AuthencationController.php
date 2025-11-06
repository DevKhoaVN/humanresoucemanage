<?php
require_once __DIR__.'/../services/AuthencationService.php';
require_once __DIR__.'/../helper/RequestHelper.php';

use services\AuthencationService;
use helper\RequestHelper;
class AuthencationController
{
    private  AuthencationService $authencationService;

    public function __construct()
    {
        $this->authencationService = new AuthencationService();
    }

    public function login(){

        try {
            $data = RequestHelper::getJsonBody();
            $email = $data['username'];
            $password = $data['password'];
            $response = $this->authencationService->login($email, $password);

            header('Content-Type: application/json');
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
                'message' => $e->getMessage()
            ]);
        }

    }


   public function  register()
   {

       try {
           $data = RequestHelper::getJsonBody();
           $username = $data['username'];
           $password = $data['password'];

           $response = $this->authencationService->register($username, $password);
           echo json_encode($response);

       } catch (Exception $e) {
           http_response_code(500);
           echo json_encode([
               'code' => 500,
               'status' => 'error',
               'message' => $e->getMessage()
           ]);
       }

   }
  public function logout()
  {
      try {

          $result = $this->authencationService->logout();

          http_response_code(200);
          echo json_encode([
              'code' => 200,
              'status' => 'success',
              'message' => 'Logout successful!'
          ]);

      }catch (Exception $e){
          http_response_code(500);
          echo json_encode([
              'code' => 500,
              'status' => 'error',
              'message' => $e->getMessage()
          ]);
      }

  }


}