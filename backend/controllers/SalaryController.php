<?php


require_once __DIR__ . "/../helper/RequestHelper.php";
require_once __DIR__ . "/../services/SalaryService.php";
require_once __DIR__ . "/../models/Salary.php";


use helper\RequestHelper;
use models\Salary;
use services\SalaryService;


class SalaryController
{
    private SalaryService $salaryService;

    public function __construct(){
        $this->salaryService = new SalaryService();
    }

    public function saveSalary( ) {

        // hash code
        $month = date('m');
        $year  = date('Y');

        try {

            $result = $this->salaryService->saveSalary($month, $year);

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

    public function gettAllSalaryInMonth( ){

        try {

            $data = RequestHelper::getJsonBody();
            $year = $data['year'];
            $month = $data['month'];
            $result = $this->salaryService->getSalaryByMonthYear($month , $year);

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