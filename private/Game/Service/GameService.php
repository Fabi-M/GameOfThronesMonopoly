<?php

namespace GameOfThronesMonopoly\Game\Service;

use GameOfThronesMonopoly\Game\Entities\game;
use GameOfThronesMonopoly\Game\Factories\GameFactory;

class GameService
{
    public function GetGameBySessionId($em, $sessionId){
        $game = GameFactory::filterOne($em, array(
            'WHERE' => array('sessionId', 'equal', $sessionId)
        ));
        if(!isset($game)){
            $game = $this->CreateNewGame($em, $sessionId);
            $playerService = new PlayerService();
            for($i = 1; $i<=$game->getGameEntity()->getMaxActivePlayers(); $i++){
                $playerService->CreatePlayer($em, $sessionId, $i, $game->getGameEntity()->getId());
            }
        }
        return $game;
    }

    public function CreateNewGame($em, $sessionId){
        $game = new \GameOfThronesMonopoly\Game\Model\Game(new game());
        $gameEntity = $game->getGameEntity();
        $gameEntity->setActivePlayerId(1);
        $gameEntity->setMaxActivePlayers(4); // todo add possiblity to set custom max player
        $gameEntity->setSessionId($sessionId);
        $em->persist($gameEntity);
        $em->flush();
        return $game;
    }
}