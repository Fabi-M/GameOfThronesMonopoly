<?php

namespace GameOfThronesMonopoly\Game\Model;

use GameOfThronesMonopoly\Core\Datamapper\EntityManager;

class Game
{


    public const MAX_PLAY_FIELDS = 39; // 0-39
    private \GameOfThronesMonopoly\Game\Entities\Game $gameEntity;


    /**
     * @param \GameOfThronesMonopoly\Game\Entities\game $gameEntity
     */
    public function __construct(\GameOfThronesMonopoly\Game\Entities\game $gameEntity)
    {
        $this->gameEntity = $gameEntity;
    }

    /**
     * @return \GameOfThronesMonopoly\Game\Entities\game
     */
    public function getGameEntity(): \GameOfThronesMonopoly\Game\Entities\game
    {
        return $this->gameEntity;
    }

    /**
     * @param \GameOfThronesMonopoly\Game\Entities\game $gameEntity
     * @return Game
     */
    public function setGameEntity(\GameOfThronesMonopoly\Game\Entities\game $gameEntity): Game
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
    }
}