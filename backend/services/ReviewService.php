<?php

namespace services;
require_once __DIR__ ."/../models/Review.php";


use models\Review;

class ReviewService
{
    public function getAllReviews(){
        $reviews =   Review::FindAll();
        $result = [];

        foreach($reviews as $review){
            $result[] = $review->toArray();
        }

        return $result;
    }

    public function getReviewById($id){
        return Review::FindById($id);
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