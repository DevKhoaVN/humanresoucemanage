<?php

namespace services;
require_once __DIR__ ."/../models/Review.php";
require_once __DIR__ ."/../config/INITDB.php";

use config\INITDB;
use models\Review;

class ReviewService
{
    private \PDO $pdo;
    public function __construct(){
        $this->pdo = INITDB::getInstance()->getConnection();
    }
    public function getAllReviews(int $month, int $year)
    {

        try {
            $query = "SELECT * FROM review WHERE MONTH(created_at) = :month AND YEAR(created_at) = :year";
            $stmt = $this->pdo->prepare($query);
            $stmt->setFetchMode(\PDO::FETCH_CLASS, Review::class);
            $stmt->execute(["month" => $month, "year" => $year]);
            $reviews = $stmt->fetchAll();
            $result = [];
            foreach ($reviews as $review) {
                $result[] = $review->toArray();
            }
            return $result;
        } catch (\Exception $e) {
            throw new \Exception("Lỗi khi lấy dữ liệu review: " . $e->getMessage());
        }
    }


    public function getReviewByIdOrMonth(int $employee_id, ?string $date = null)
    {
        try {
            // Nếu có date được truyền vào (ví dụ "2025-11")
            if ($date) {
                // Tách tháng và năm từ chuỗi
                $year = date('Y', strtotime($date));
                $month = date('m', strtotime($date));

                $query = "SELECT * FROM review
                      WHERE employee_id = :employee_id
                      AND MONTH(created_at) = :month
                      AND YEAR(created_at) = :year
                      ORDER BY created_at DESC";

                $stmt = $this->pdo->prepare($query);
                $stmt->execute([
                    "employee_id" => $employee_id,
                    "month" => $month,
                    "year" => $year
                ]);
            } else {
                // Nếu không truyền date -> lấy toàn bộ đánh giá của nhân viên
                $query = "SELECT * FROM review
                      WHERE employee_id = :employee_id
                      ORDER BY created_at DESC";

                $stmt = $this->pdo->prepare($query);
                $stmt->execute([
                    "employee_id" => $employee_id
                ]);
            }

            $stmt->setFetchMode(\PDO::FETCH_CLASS, Review::class);

            $result = [];
            foreach ($stmt as $review) {
                $result[] = $review->toArray();
            }

            return $result;

        } catch (\PDOException $e) {
            throw new \Exception("Lỗi khi lấy dữ liệu review: " . $e->getMessage());
        }
    }


    public function createReview(Review $review){
        if($review == null){
            throw new \Exception("Review must not be null");
        }

        $result = $review->save();

        return $result;
    }


    public function deleteReview($id){
        $review = Review::FindById($id);
        if($review === null){
            throw new \Exception("Review must not be null");
        }

        $result = Review::DeleteById($id);
        return $result;

    }

}