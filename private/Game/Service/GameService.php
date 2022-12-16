<?php

namespace GameOfThronesMonopoly\Game\Service;

use GameOfThronesMonopoly\Game\Entities\game;
use GameOfThronesMonopoly\Game\Factories\GameFactory;

class GameService
{
    /**
     * Get a game from the db with the sessionId
     * Create new game if there is no game with the sessionId
     * @author Fabian Müller
     * @param $em
     * @param $sessionId
     * @return \GameOfThronesMonopoly\Game\Model\Game|null
     */
    public function getGameBySessionId($em, $sessionId): ?\GameOfThronesMonopoly\Game\Model\Game
    {
        $game = GameFactory::filterOne($em, array(array('sessionId', 'equal', $sessionId)));
        if(!isset($game)){
            $game = $this->createGame($em, $sessionId);
        }
        return $game;
    }

    /**
     * Create a new game
     * @author Fabian Müller
     * @param $em
     * @param $sessionId
     * @return \GameOfThronesMonopoly\Game\Model\Game
     */
    public function createGame($em, $sessionId): \GameOfThronesMonopoly\Game\Model\Game
    {
        // in game model auslagern ---
        $game = new \GameOfThronesMonopoly\Game\Model\Game(new game());
        $gameEntity = $game->getGameEntity();
        $gameEntity->setActivePlayerId(1);
        $gameEntity->setMaxActivePlayers(4); // todo add possiblity to set custom max player
        $gameEntity->setSessionId($sessionId);
        $em->persist($gameEntity);
        $em->flush();
        // ---
        $playerService = new PlayerService();
        $playerService->createAllPlayers($em, $sessionId, $gameEntity->getId(), $gameEntity->getMaxActivePlayers());
        return $game;
    }
}