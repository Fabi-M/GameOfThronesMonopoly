<?php

namespace GameOfThronesMonopoly\Game\Model;

use GameOfThronesMonopoly\Core\Datamapper\EntityManager;

class Player
{
    private $playerEntity;

    /**
     * @return \GameOfThronesMonopoly\Game\Entities\player
     */
    public function getPlayerEntity(): \GameOfThronesMonopoly\Game\Entities\player
    {
        return $this->playerEntity;
    }

    /**
     * @param $playerEntity
     */
    public function __construct($playerEntity = null)
    {
        $this->playerEntity = $playerEntity;
    }

    /**
     * Create a new player with the given data
     * @author Fabian Müller
     * @param EntityManager $em
     * @param $sessionId
     * @param $playerId
     * @param $gameId
     * @return void
     */
    public function create($em, $sessionId, $playerId, $gameId){
        $playerEntity = new \GameOfThronesMonopoly\Game\Entities\player();
        $playerEntity->setSessionId($sessionId);
        $playerEntity->setGameId($gameId);
        $playerEntity->setIngameId($playerId);
        $playerEntity->setPosition(0);
        $playerEntity->setMoney(1500);
        $em->persist($playerEntity);
    }
}