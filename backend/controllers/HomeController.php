<?php

require_once __DIR__ . '/../config/INITDB.php';
use config\INITDB;

class HomeController
{
    private PDO $pdo;

    public function __construct() {
        $this->pdo = INITDB::getInstance()->getConnection();
    }

    public function getInforDashboard(): void
    {
        try {
            $query = "
                SELECT
                    (SELECT COUNT(*) FROM employee) AS total_employee,
                    (SELECT COUNT(*) FROM department) AS total_department,
                    (SELECT COUNT(*) FROM employee WHERE is_active = 1) AS total_active_employee,
                    (SELECT COUNT(*) FROM leaves WHERE status = 1 AND leave_date = DATE('2025-10-03')) AS total_leave
            ";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode([
                'code' => 200,
                'message' => 'Success',
                'data' => $result
            ]);
        } catch (PDOException $e) {

            http_response_code(500);
            echo json_encode([
                'code' => 500,
                'message' => 'Database Error: ' . $e->getMessage()
            ]);
        } catch (Exception $e) {

            http_response_code(500);
            echo json_encode([
                'code' => 500,
                'message' => 'Server Error: ' . $e->getMessage()
            ]);
        }
    }

    public function getEmployeesDashboard(): void
    {

        try {

            $query = "SELECT 
    e.id AS id,
    e.fullname AS `fullname`,
    d.name AS `department`,
    p.name AS `position`,
    CASE 
        WHEN e.is_active = 1 THEN 'true'
        ELSE 'false'
    END AS `Trạng thái`,
    a.check_in AS `in`,
    a.check_out AS `out`
FROM attendance a
JOIN employee e ON e.id = a.employee_id
JOIN positions p ON p.id = e.position_id
JOIN department d ON d.id = p.department_id
WHERE DATE(a.check_in) = '2025-10-03';
";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'code' => 200,
                'message' => 'Success',
                'data' => $result
            ]);
        } catch (PDOException $e) {

            http_response_code(500);
            echo json_encode([
                'code' => 500,
                'message' => 'Database Error: ' . $e->getMessage()
            ]);
        } catch (Exception $e) {

            http_response_code(500);
            echo json_encode([
                'code' => 500,
                'message' => 'Server Error: ' . $e->getMessage()
            ]);
        }

    }
}
