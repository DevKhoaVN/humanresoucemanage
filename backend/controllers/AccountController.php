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
        header('Content-Type: application/json');

        try {
            $result = $this->accountService->getAllAccounts();
            http_response_code(200);
            echo json_encode($result);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getAccountById(){
      header('Content-Type: application/json');
        try {

            $data = RequestHelper::getJsonBody();
            $id = $data['id'];
            $result = $this->accountService->getAccount($id);
            http_response_code(200);
            echo json_encode($result);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error : ' => $e->getMessage()]);
        }
    }
    public function updateAccount( ){
      header('Content-Type: application/json');
        try {

            $data = RequestHelper::getJsonBody();
            $infor =  Account::mapToObject($data);
            $infor->setCreateAt(null);

            echo "dât : ". var_dump($infor);
            $result = $this->accountService->updateAccount($infor);
            http_response_code(200);
            echo json_encode($result);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error : ' => $e->getMessage()]);
        }

    }

    public function deleteAccountById($id){
      header('Content-Type: application/json');

        try {
            $result = $this->accountService->deleteAccount($id);
            http_response_code(200);
            echo json_encode($result);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error : ' => $e->getMessage()]);
        }

    }

    public function  enableRole($id){
      header('Content-Type: application/json');

        try {
            $result = $this->accountService->eanbleRole($id);
            http_response_code(200);
            echo json_encode($result);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error : ' => $e->getMessage()]);
        }
    }
    public function  disableRole($id){
        header('Content-Type: application/json');

        try {
            $result = $this->accountService->disableRole($id);
            http_response_code(200);
            echo json_encode($result);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error : ' => $e->getMessage()]);
        }
    }


}