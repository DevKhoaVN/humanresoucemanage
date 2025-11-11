<?php

namespace services;

require_once __DIR__ ."/../models/Attendance.php";
require_once __DIR__ ."/../helper/RequestHelper.php";
require_once __DIR__ ."/../config/INITDB.php";

use config\INITDB;
use models\Attendance;
use helper\RequestHelper;
use PDO;

class AttendanceService
{
    private Attendance  $attendance;
    private PDO $pdo;

    public function __construct(){
        $this->attendance = new Attendance();
        $this->pdo = INITDB::getInstance()->getConnection();
    }

    public function getAllAttendance($work_date) {
        try {
            $query = "SELECT * 
                  FROM attendance 
                  WHERE DATE(work_date) = :work_date
                  ORDER BY id DESC";

            $stmt = $this->pdo->prepare($query);
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Attendance::class);
            $stmt->execute([
                "work_date" => $work_date
            ]);

            $res =  $stmt->fetchAll();
            $reuslt = [];
            foreach ($res as $attendance) {
                $reuslt[] = $attendance->toArray();
            }

            return $reuslt;

        } catch (\PDOException $e) {
            throw new \Exception("Lỗi khi lấy dữ liệu attendace: " . $e->getMessage());
        }
    }

    public function findAttendanceByIdOrDate($employee_id, $work_date = null) {
        try {
            if ($work_date) {
                // Nếu có ngày, lọc theo ngày
                $query = "SELECT * FROM attendance 
                      WHERE employee_id = :employee_id 
                      AND DATE(work_date) = :work_date
                      ORDER BY work_date DESC";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([
                    "employee_id" => $employee_id,
                    "work_date" => $work_date
                ]);
            } else {
                // Nếu không có ngày, lấy tất cả bản ghi của nhân viên
                $query = "SELECT * FROM attendance 
                      WHERE employee_id = :employee_id
                      ORDER BY work_date DESC";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([
                    "employee_id" => $employee_id
                ]);
            }

            $stmt->setFetchMode(PDO::FETCH_CLASS, Attendance::class);

            $reuslt = [];
            foreach ($stmt as $attendance) {
                $reuslt[] = $attendance->toArray();
            }

            return $reuslt ;

        } catch (\PDOException $e) {
            throw new \Exception("Lỗi khi lấy dữ liệu attendace: " . $e->getMessage());
        }
    }

    public function updateAttendance( Attendance $attendance)
    {
        if ($attendance === null) {
            throw new \Exception("Attendace must not null");
        }

        $result = $attendance->save();

        return $result ? true : false;
    }


    public function checkIn(int $employee_id,  string $check_in, string $note = null)
    {
        try {
            $query = "INSERT INTO attendance (employee_id, check_in, note) 
                       VALUES (:employee_id, :check_in, :note)";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                "check_in" => $check_in,
                "note" => $note,
                "employee_id" => $employee_id
            ]);
            return $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            error_log("CheckIn Error: " . $e->getMessage());
            return false;
        }
    }

    public function checkOut(int $employee_id, string $check_out, string $work_date ,string $note = null)
    {
        try {
            $query = "UPDATE attendance SET check_out = :check_out, note = :note 
                  WHERE employee_id = :employee_id AND DATE(work_date) = :work_date";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                "check_out" => $check_out, // sửa key đúng
                "note" => $note,
                "employee_id" => $employee_id,
                "work_date" => $work_date
            ]);
            return $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            throw new \Exception("Lỗi khi lấy dữ liệu attendace: " . $e->getMessage());
        }
    }

    public function deleteAttendace( int $id){
        if($id === null){
            throw new \Exception("Attendace id  must not null");
        }
        $result = Attendance::deleteById($id);

        return $result;
    }

}