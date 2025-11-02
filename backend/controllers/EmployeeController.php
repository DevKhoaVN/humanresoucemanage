<?php

require_once __DIR__ . '/../services/EmployeeService.php';
require_once __DIR__ . '/../helper/RequestHelper.php';
use services\EmployeeService;
use helper\RequestHelper;
use models\Employee;

class EmployeeController
{

    private EmployeeService $employeeService;

    public function __construct(){
        $this->employeeService = new EmployeeService();
    }

    public function getAllEmployee() {
        header('Content-Type: application/json');

        try {
            $result = $this->employeeService->getAllEmployee();
            http_response_code(200);
            echo json_encode($result);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getEmployeeById()
    {
       header('Content-Type: application/json');
        try {

            $data = RequestHelper::getJsonBody();
            $id = $data["id"];
            $result = $this->employeeService->getEmployeeById($id);
            http_response_code(200);
            echo json_encode($result);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function createEmployee(){
        header('Content-Type: application/json');
        try {

            $data = RequestHelper::getJsonBody();
            $employee = Employee::mapToObject($data);

            $result = $this->employeeService->updateEmployee($employee);
            http_response_code(200);
            echo json_encode($result);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }


    public function deleteEmployee(){
        header('Content-Type: application/json');

        try {

            $data = RequestHelper::getJsonBody();
            $id = $data["id"];

            $result = $this->employeeService->deleteEmployee($id);
            http_response_code(200);
            echo json_encode($result);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function searchEmployee()
    {
        header('Content-Type: application/json');

        try {

            $data = RequestHelper::getJsonBody();
            $searchTemp = $data["searchTemp"];

            $result = $this->employeeService->searchEmployee($searchTemp);
            http_response_code(200);
            echo json_encode($result);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

}