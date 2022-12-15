<?php

namespace GameOfThronesMonopoly\Game\Service;

use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Entities\player;

class PlayerService
{
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
            $player = new \GameOfThronesMonopoly\Game\Model\Player();
            $player->create($em, $sessionId, $i, $gameId);
        }
    }
}