<?php

namespace GameOfThronesMonopoly\Game\Service;

use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Entities\player;

class PlayerService
{
    /**
     * Create a new player with the given data
     * @author Fabian Müller
     * @param EntityManager $em
     * @param $sessionId
     * @param $playerId
     * @param $gameId
     * @return void
     */
    public function createPlayer($em, $sessionId, $playerId, $gameId){
        $playerEntity = new player();
        $playerEntity->setSessionId($sessionId);
        $playerEntity->setGameId($gameId);
        $playerEntity->setIngameId($playerId);
        $playerEntity->setPosition(0);
        $playerEntity->setMoney(1500);
        $em->persist($playerEntity);
    }

    /**
     *
     * @author Fabian Müller
     * @param $em
     * @param $sessionId
     * @param $gameId
     * @param $maxPlayerCount
     * @return void
     */
    public function createAllPlayers($em, $sessionId, $gameId, $maxPlayerCount){
        for($i = 1; $i<=$maxPlayerCount; $i++){
            $this->createPlayer($em, $sessionId, $i, $gameId);
        }
    }
}