<?php

namespace GameOfThronesMonopoly\Game\Model;

use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Entities\Game as gameEntity;

class Game
{


    public const MAX_PLAY_FIELDS = 39; // 0-39
    private ?gameEntity $gameEntity;


    /**
     * @param gameEntity|null $gameEntity $gameEntity
     */
    public function __construct(gameEntity $gameEntity = null)
    {
        $this->gameEntity = $gameEntity;
    }

    /**
     * @return gameEntity
     */
    public function getGameEntity(): gameEntity
    {
        return $this->gameEntity;
    }

    /**
     * @param gameEntity $gameEntity
     * @return Game
     */
    public function setGameEntity(gameEntity $gameEntity): Game
    {
        $this->gameEntity = $gameEntity;
        return $this;
    }

    /**
     * End the current turn, set next player as active
     * @param EntityManager $em
     * @return bool|int
     * @author Fabian Müller
     */
    public function endTurn(EntityManager $em): bool|int
    {
        if(!((bool) $this->gameEntity->getAllowedToEndTurn())) return false;
        $playerId = $this->gameEntity->getActivePlayerId()+1;
        $maxPlayerCount = $this->gameEntity->getMaxActivePlayers();
        if($playerId > $maxPlayerCount){
            $playerId -= $maxPlayerCount;
        }
        $this->gameEntity->setActivePlayerId($playerId);
        $this->gameEntity->setAllowedToEndTurn(false);
        $em->persist($this->gameEntity);
        return $playerId;
    }

    /**
     * Create a new game
     * @author Fabian Müller
     * @param EntityManager $em
     * @param $sessionId
     * @return void
     */
    public function create(EntityManager $em, $sessionId){
        $this->gameEntity = new gameEntity();
        $this->gameEntity->setActivePlayerId(1);
        $this->gameEntity->setMaxActivePlayers(4); // todo add possiblity to set custom max player
        $this->gameEntity->setSessionId($sessionId);
        $this->gameEntity->setAllowedToEndTurn(0);
        $em->persist($this->gameEntity);

    }
}