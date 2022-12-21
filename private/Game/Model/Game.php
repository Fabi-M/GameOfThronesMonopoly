<?php

namespace GameOfThronesMonopoly\Game\Model;

use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Entities\Game as gameEntity;

class Game
{


    public const MAX_PLAY_FIELDS = 39; // 0-39
    private gameEntity $gameEntity;


    /**
     * @param gameEntity $gameEntity
     */
    public function __construct(gameEntity $gameEntity)
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
     * @author Fabian Müller
     * @param EntityManager $em
     * @return void
     */
    public function endTurn(EntityManager $em){
        $playerId = $this->gameEntity->getActivePlayerId()+1;
        $maxPlayerCount = $this->gameEntity->getMaxActivePlayers();
        if($playerId > $maxPlayerCount){
            $playerId -= $maxPlayerCount;
        }
        $this->gameEntity->setActivePlayerId($playerId);
        $em->persist($this->gameEntity);
        return $playerId;
    }
}