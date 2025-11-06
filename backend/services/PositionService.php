<?php

namespace services;
require_once __DIR__ ."/../models/Position.php";


use config\INITDB;
use models\Position;
class PositionService
{

    public function getAllPositions(){

        $postions =  Position::FindAll();
        $result = [];
        foreach($postions as $position){
            $result[]=  $position->toArray();
        }

        return $result;
    }


    public function createPosition(Position $position){
        if($position == null){
            throw new \Exception("Employee must not be null");
        }

        $result = $position->save();

        return $result;
    }



    public function deletePosition($id){

        $position = Position::FindById($id);
        if($position === null){
            throw new \Exception("Employee must not be null");
        }

        $result  =Position::deleteById($id);

        return $result;

    }

}