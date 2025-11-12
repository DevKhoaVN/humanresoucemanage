<?php

namespace services;
require_once __DIR__ ."/../models/Salary.php";
require_once __DIR__ ."/../config/INITDB.php";
require_once __DIR__ ."/../models/Salary.php";


use config\INITDB;
use models\salary;
use PDO;
use PDOException;

class SalaryService
{

    private PDO $pdo;

    public function __construct(){
        $this->pdo = INITDB::getInstance()->getConnection();
    }
    private function caculateSalaryInMonth(int $month, int $year)
    {
        try {
            $query = "
            SELECT 
                e.id AS employee_id,
                p.base_salary,
                IFNULL(a.work_days, 0) AS work_days,
                IFNULL(l.absent_days, 0) AS absent_days,
                p.base_salary - (p.base_salary / 30 * IFNULL(l.absent_days, 0)) AS total_salary
            FROM employee e
            LEFT JOIN positions p ON e.position_id = p.id
            LEFT JOIN (
                SELECT employee_id, COUNT(*) AS work_days
                FROM attendance
                WHERE MONTH(work_date) = :month AND YEAR(work_date) = :year
                GROUP BY employee_id
            ) a ON e.id = a.employee_id
            LEFT JOIN (
                SELECT employee_id, COUNT(*) AS absent_days
                FROM leaves
                WHERE MONTH(leave_date) = :month AND YEAR(leave_date) = :year
                GROUP BY employee_id
            ) l ON e.id = l.employee_id
        ";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':month' => $month,
                ':year'  => $year
            ]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            throw new \Exception("Lỗi khi lấy dữ liệu salary: " . $e->getMessage());
        }
    }


    public  function saveSalary( int $month, int $year){
        try {
            $salaries = $this->caculateSalaryInMonth($month, $year);

            foreach ($salaries as $salary) {
                $stmt = $this->pdo->prepare("
                INSERT INTO salary (
                    employee_id, base_salary, work_days, absent_days, total_salary
                ) VALUES (
                    :employee_id, :base_salary, :work_days, :absent_days, :total_salary
                )
            ");

                $stmt->execute([
                    ':employee_id' => $salary['employee_id'],
                    ':base_salary' => $salary['base_salary'],
                    ':work_days' => $salary['work_days'],
                    ':absent_days' => $salary['absent_days'],
                    ':total_salary' => $salary['total_salary']
                ]);
            }

            return true;

        }catch (PDOException $e) {
            throw new \Exception("Lỗi khi lấy dữ liệu salary: " . $e->getMessage());
        }
    }

    public function getSalaryByMonthYear(int $month, int $year)
    {
        try {
            $pdo = INITDB::getInstance()->getConnection();

            $query = "
            SELECT 
                id,
                employee_id,
                base_salary,
                work_days,
                absent_days,
                total_salary,
                created_at
            FROM salary
            WHERE MONTH(created_at) = :month
              AND YEAR(created_at) = :year
        ";

            $stmt = $pdo->prepare($query);

            $stmt->setFetchMode(PDO::FETCH_CLASS , Salary::class);
            $stmt->execute([
                ':month' => $month,
                ':year'  => $year
            ]);

            $ob =  $stmt->fetchAll();

            $result = [];

            foreach ($ob as $salary) {
                $result[] = $salary->toArray();
            }

             return $result;
        } catch (PDOException $e) {
            throw new \Exception("Lỗi khi lấy dữ liệu salary: " . $e->getMessage());
        }
    }




}