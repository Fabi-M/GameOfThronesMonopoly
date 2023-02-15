<?php

namespace GameOfThronesMonopoly\Game\Model;

use Exception;
use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Entities\Game as gameEntity;
use GameOfThronesMonopoly\Game\Factories\PlayerFactory;
use GameOfThronesMonopoly\Game\Factories\StreetFactory;

class Game
{
    public const MAX_PLAY_FIELDS = 39; // 0-39

    private $gameEntity;

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
     * @return array
     * @throws Exception
     * @author Fabian Müller
     */
    public function endTurn(EntityManager $em): array
    {
        if (!((bool) $this->gameEntity->getAllowedToEndTurn())) {
            throw new Exception("Player is not allowed to end turn!");
        }
        $playerId = $this->gameEntity->getActivePlayerId() + 1;
        $maxPlayerCount = $this->gameEntity->getMaxActivePlayers();
        if ($playerId > $maxPlayerCount) {
            $playerId -= $maxPlayerCount;
        }
        $this->gameEntity->setActivePlayerId($playerId);
        $this->gameEntity->setAllowedToEndTurn(0);
        $this->gameEntity->setRolledDice(0);
        $em->persist($this->gameEntity);
        $playerEntity = PlayerFactory::getActivePlayer($em, $this)->getPlayerEntity();
        $streets = StreetFactory::getAllByPlayerId($em, $playerEntity->getId());
        $streetNames = array_map(function (Street $street) {
            return $street->getSimpleInfo();
        }, $streets);
        return [
            "money" => $playerEntity->getMoney(),
            "playerId" => $playerEntity->getId(),
            "position" => $playerEntity->getPosition(),
            "gameId" => $playerEntity->getSessionId(),
            "ingameId" => $playerEntity->getIngameId(),
            "sessionId" => $playerEntity->getSessionId(),
            'streets' => implode('<br>---------------<br>', $streetNames),
            "success" => true
        ];
    }

    /**
     * Create a new game
     * @author Fabian Müller
     * @param EntityManager $em
     * @param $sessionId
     * @return void
     */
    public function create(EntityManager $em, $sessionId, $playerCount)
    {
        $this->gameEntity = new gameEntity();
        $this->gameEntity->setActivePlayerId(1);
        $this->gameEntity->setMaxActivePlayers($playerCount);
        $this->gameEntity->setSessionId($sessionId);
        $this->gameEntity->setAllowedToEndTurn(0);
        $em->persist($this->gameEntity);

    }
}