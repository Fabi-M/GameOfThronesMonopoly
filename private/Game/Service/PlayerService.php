<?php

namespace GameOfThronesMonopoly\Game\Service;

use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Entities\Player;

class PlayerService
{
    /**
     * Create all players for the new game
     * @author Fabian Müller
     * @param $em
     * @param $gameId
     * @param $maxPlayerCount
     * @return void
     */
    public function createAllPlayers($em, $gameId, $maxPlayerCount){
        for($i = 1; $i<=$maxPlayerCount; $i++){
            $player = new \GameOfThronesMonopoly\Game\Model\Player();
            $player->create($em, $i, $gameId);
        }
    }
}