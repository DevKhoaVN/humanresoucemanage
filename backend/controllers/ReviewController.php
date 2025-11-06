<?php


require_once __DIR__ . "/../helper/RequestHelper.php";
require_once __DIR__ . "/../services/ReviewService.php";
require_once __DIR__ . "/../models/Review.php";


use helper\RequestHelper;
use models\Review;
use services\ReviewService;


class ReviewController
{
    private ReviewService $reviewService;

    public function __construct(){
        $this->reviewService = new ReviewService();
    }

    public function getAllReviews( ) {

        try {

            $result = $this->reviewService->getAllReviews();

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

    public function createReview(){

        try {

            $data = RequestHelper::getJsonBody();

            $newReview = Review::mapToObject($data);

            $result = $this->reviewService->createReview($newReview);

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

    public function deleteReview( ){

        try {

            $data = RequestHelper::getJsonBody();
            $id = $data['id'];
            $result = $this->reviewService->deleteReview($id);

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