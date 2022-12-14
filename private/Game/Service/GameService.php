<?php

namespace GameOfThronesMonopoly\Game\Service;

use GameOfThronesMonopoly\Game\Factories\GameFactory;

class GameService
{
    public function GetGameBySessionId($em, $sessionId){
        $game = GameFactory::filterOne($em, array(
            'WHERE' => array('sessionId', 'equal', $sessionId)
        ));
        if(!isset($game)){
            $this->CreateNewGame();
            var_dump("NO GAME YET");
        }
        return $game;
    }

    public function CreateNewGame(){

    }
}