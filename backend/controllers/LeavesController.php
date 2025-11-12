<?php


require_once __DIR__ . "/../helper/RequestHelper.php";
require_once __DIR__ . "/../services/LeaveService.php";
require_once __DIR__ . "/../models/Leaves.php";


use helper\RequestHelper;
use models\Employee;
use models\Leaves;
use services\LeaveService;


class LeavesController
{
    private LeaveService $leavesService;

    public function __construct(){
        $this->leavesService = new LeaveService();
    }

    public function getAllLeaves( ){

        try {

            $data = RequestHelper::getJsonBody();
            $date = $data['date'];
            $result = $this->leavesService->getAllLeaves($date);

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

    public function createLeaves(){

        try {

            $data = RequestHelper::getJsonBody();

            $newLeaves = Leaves::mapToObject($data);

            $result = $this->leavesService->updateLeaves($newLeaves);

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

    public function deleteLeaves( ){

        try {

            $data = RequestHelper::getJsonBody();
            $id = $data['id'];
            $result = $this->leavesService->deleteAccount($id);

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