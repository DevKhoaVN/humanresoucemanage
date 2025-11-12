<?php


require_once __DIR__ .'/../vendor/autoload.php';
require_once __DIR__ .'/../services/AccountService.php';
require_once __DIR__ .'/../helper/RequestHelper.php';

use models\Account;
use services\AccountService;
use helper\RequestHelper;

class AccountController
{
    private AccountService $accountService;

    public function __construct(){
      $this->accountService = new AccountService();
  }

    public function getAllAccounts() {
        try {
            $result = $this->accountService->getAllAccounts();
            http_response_code(200);

            echo json_encode([
                'code' => 200,
                'message' => 'Success',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getAccountById(){

        try {

            $data = RequestHelper::getJsonBody();
            $id = $data['id'];
            $result = $this->accountService->getAccount($id);

            http_response_code(200);
            echo json_encode([
                'code' => 200,
                'message' => 'Success',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error : ' => $e->getMessage()]);
        }
    }
    public function updateAccount( ){

        try {

            $data = RequestHelper::getJsonBody();
            $infor =  Account::mapToObject($data);


            $result = $this->accountService->updateAccount($infor);
            http_response_code(200);
            echo json_encode([
                'code' => 200,
                'message' => 'Success',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error : ' => $e->getMessage()]);
        }

    }

    public function deleteAccountById(){

        try {

            $data = RequestHelper::getJsonBody();
            $id = $data['id'];
            $result = $this->accountService->deleteAccount($id);
            http_response_code(200);


            echo json_encode([
                'code' => 200,
                'message' => 'Success',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error : ' => $e->getMessage()]);
        }

    }


}