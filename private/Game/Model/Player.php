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
     * @param EntityManager $em
     * @param               $sessionId
     * @param               $playerId
     * @param               $gameId
     * @return void
     * @author Fabian Müller
     */
    public function create($em, $sessionId, $playerId, $gameId)
    {
        $playerEntity = new \GameOfThronesMonopoly\Game\Entities\player();
        $playerEntity->setSessionId($sessionId);
        $playerEntity->setGameId($gameId);
        $playerEntity->setIngameId($playerId);
        $playerEntity->setPosition(0);
        $playerEntity->setMoney(1500);
        $em->persist($playerEntity);
    }

    /**
     * @author Selina Stöcklein
     * @param EntityManager $em
     * @param array         $rolled
     * @return void
     */
    public function move(EntityManager $em, array $rolled)
    {
        $oldPosition = $this->getPlayerEntity()->getPosition();
        // 0-39 felder
        $newPosition = $oldPosition + array_sum($rolled);
        $newPosition = $newPosition <= Game::MAX_PLAY_FIELDS ? $newPosition : $newPosition - 40;
        $this->getPlayerEntity()->setPosition($newPosition);
        $em->persist($this->getPlayerEntity());
        return $newPosition;
    }
}