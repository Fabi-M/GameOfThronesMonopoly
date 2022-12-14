<?php

namespace GameOfThronesMonopoly\Game\Model;

use GameOfThronesMonopoly\Core\Datamapper\EntityManager;

class Game
{
    private \GameOfThronesMonopoly\Game\Entities\Game $gameEntity;

    /**
     * @param \GameOfThronesMonopoly\Game\Entities\Game $gameEntity
     */
    public function __construct(\GameOfThronesMonopoly\Game\Entities\Game $gameEntity)
    {
        $this->gameEntity = $gameEntity;
    }

    /**
     * @return \GameOfThronesMonopoly\Game\Entities\Game
     */
    public function getGameEntity(): \GameOfThronesMonopoly\Game\Entities\Game
    {
        return $this->gameEntity;
    }

    /**
     * @param \GameOfThronesMonopoly\Game\Entities\Game $gameEntity
     * @return Game
     */
    public function setGameEntity(\GameOfThronesMonopoly\Game\Entities\Game $gameEntity): Game
    {
        $this->gameEntity = $gameEntity;
        return $this;
    }

    public function endTurn(EntityManager $em){
        $playerId = $this->gameEntity->getActivePlayerId()+1;
        $maxPlayerCount = $this->gameEntity->getMaxActivePlayers();
        if($playerId > $maxPlayerCount){
            $playerId -= $maxPlayerCount;
        }
        $this->gameEntity->setActivePlayerId($playerId);
        $em->persist($this->gameEntity);
        var_dump($playerId);
    }
}