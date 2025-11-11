<?php

require_once __DIR__ .'/../services/DepartmentService.php';
require_once __DIR__ .'/../helper/RequestHelper.php';

use models\Department;
use services\DepartmentService;
use helper\RequestHelper;

class DepartmentController
{
    private DepartmentService $departmentService;

    public function __construct(){
        $this->departmentService = new DepartmentService();
    }
    public function getAllDepartments() {
        try {
            $result = $this->departmentService->getAllDepartments();
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

    public function updateDepartment( ){

        try {

            $data = RequestHelper::getJsonBody();
            $newDepartment = Department::mapToObject($data);
            $result = $this->departmentService->updateDepartment($newDepartment);

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
    public function deleteDepartment( ){

        try {

            $data = RequestHelper::getJsonBody();
            $id = $data['id'];

            $result = $this->departmentService->deleteDepartment($id);

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