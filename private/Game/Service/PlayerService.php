<?php

namespace GameOfThronesMonopoly\Game\Service;

use GameOfThronesMonopoly\Game\Entities\player;

class PlayerService
{
    public function CreatePlayer($em, $sessionId, $playerId, $gameId){
        $playerEntity = new player();
        $playerEntity->setSessionId($sessionId);
        $playerEntity->setGameId($gameId);
        $playerEntity->setIngameId($playerId);
        $playerEntity->setPosition(0);
        $playerEntity->setMoney(1500);
        $em->persist($playerEntity);
    }
}