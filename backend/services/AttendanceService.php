<?php

namespace services;

require_once __DIR__ ."/../models/Attendance.php";
require_once __DIR__ ."/../helper/RequestHelper.php";

use config\INITDB;
use models\Attendance;
use helper\RequestHelper;

class AttendanceService
{
    private Attendance  $attendance;

    public function __construct(){
        $this->attendance = new Attendance();
    }

    public function getAllLAttendance() : array{
        $attendances = Attendance::findAll();
        $result = [];
        foreach ($attendances as $attendance) {
            $result[] = $attendance->toArray();
        }

        return $result;

    }
    public function updateAttendance( Attendance $attendance)
    {
        if ($attendance === null) {
            throw new \Exception("Attendace must not null");
        }

        $result = $attendance->save();

        return $result ? true : false;
    }


    public function deleteAttendace( int $id){
        if($id === null){
            throw new \Exception("Attendace id  must not null");
        }
        $result = Attendance::deleteById($id);

        return $result;
    }

}