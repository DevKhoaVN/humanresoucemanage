<?php

require_once __DIR__ .'/../services/AttendanceService.php';
require_once __DIR__ .'/../helper/RequestHelper.php';

use models\Attendance;
use services\AttendanceService;
use helper\RequestHelper;

class AttendanceController
{
    private AttendanceService $attendanceService;

    public function __construct(){
        $this->attendanceService = new AttendanceService();
    }
    public function getAllAttendances() {
        try {
            $data =  RequestHelper::getJsonBody();
            $work_date = $data['work_date'];
            $result = $this->attendanceService->getAllAttendance($work_date);
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

    public function checkIn() {
        try {
            $data =  RequestHelper::getJsonBody();
            $employee_id = $data['employee_id'];
            $check_in = $data['check_in'];
            $result = $this->attendanceService->checkIn($employee_id , $check_in);
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

    public function checkOut() {
        try {
            $data =  RequestHelper::getJsonBody();
            $employee_id = $data['employee_id'];
            $check_out = $data['check_out'];
            $work_date = $data['work_date'];
            $result = $this->attendanceService->checkOut($employee_id , $check_out , $work_date);
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


    public function updateAttendance( ){

        try {

            $data = RequestHelper::getJsonBody();
            $newAttendance =  Attendance::mapToObject($data);
            echo"department : ".var_dump($newAttendance);
            $result = $this->attendanceService->updateAttendance($newAttendance);

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

    public function findAttendanceById( ){

        try {

            $data = RequestHelper::getJsonBody();
            $employee_id = $data["employee_id"] ?? null;
            $work_date = $data["work_date"] ?? null;


            $result = $this->attendanceService->findAttendanceByIdOrDate($employee_id , $work_date);

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
    public function deleteAttendance(){

        try {

            $data = RequestHelper::getJsonBody();
            $id = $data['id'];

            $result = $this->attendanceService->deleteAttendace($id);

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