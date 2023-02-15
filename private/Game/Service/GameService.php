<?php

namespace GameOfThronesMonopoly\Game\Service;

use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Entities\game;
use GameOfThronesMonopoly\Game\Factories\GameFactory;

class GameService
{
    private $game;
    private EntityManager $em;

    /**
     * Get a game from the db with the sessionId
     * Create new game if there is no game with the sessionId
     * @param $em
     * @param $sessionId
     * @return \GameOfThronesMonopoly\Game\Model\Game|null
     * @throws \Exception
     * @author Fabian Müller
     */
    public function getGameBySessionId($em, $sessionId): ?\GameOfThronesMonopoly\Game\Model\Game
    {
        $this->em = $em;
        $this->game = GameFactory::filterOne($em, array(array('sessionId', 'equal', $sessionId)));
        if(!isset($this->game)){
            throw new \Exception("no game found");
        }
        return $this->game;
    }

    /**
     * Create a new game
     * @author Fabian Müller
     * @param $em
     * @param $sessionId
     * @return \GameOfThronesMonopoly\Game\Model\Game
     */
    public function createGame($em, $sessionId, $playerCount): \GameOfThronesMonopoly\Game\Model\Game
    {

        $this->em = $em;
        $game = new \GameOfThronesMonopoly\Game\Model\Game();
        $game->create($em, $sessionId, $playerCount);
        $em->flush();

        $playerService = new PlayerService();
        $playerService->createAllPlayers($em, $sessionId, $game->getGameEntity()->getId(), $playerCount);

        return $game;
    }

    /**
     * Check if the player is allowed to end turn
     * @author Fabian Müller
     * @param $rolled
     * @return void
     */
    public function checkIfAllowedToEndTurn($rolled){
        if($rolled != array_unique($rolled)) return;
        $this->game->getGameEntity()->setAllowedToEndTurn(1);
        $this->em->persist($this->game->getGameEntity());
    }
}