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
            $result = $this->attendanceService->getAllLAttendance();
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