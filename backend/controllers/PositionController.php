<?php


require_once __DIR__ . "/../helper/RequestHelper.php";
require_once __DIR__ . "/../services/PositionService.php";
require_once __DIR__ . "/../models/Position.php";


use helper\RequestHelper;
use models\Position;
use services\PositionService;


class PositionController
{
    private PositionService $positionService;

    public function __construct(){
        $this->positionService = new PositionService();
    }

    public function getAllPositions( ) {

        try {

            $result = $this->positionService->getAllPositions();

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

    public function createPostion(){

        try {

            $data = RequestHelper::getJsonBody();

            $newPostion = Position::mapToObject($data);

            $result = $this->positionService->createPosition($newPostion);

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

    public function deletePostion( ){

        try {

            $data = RequestHelper::getJsonBody();
            $id = $data['id'];
            $result = $this->positionService->deletePosition($id);

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