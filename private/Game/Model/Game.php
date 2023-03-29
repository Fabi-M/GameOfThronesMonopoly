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
        $gameEntity = $this->gameEntity;
        $gameEntity->setPaschCount(0);
        if (!((bool) $gameEntity->getAllowedToEndTurn())) {
            throw new Exception("Player is not allowed to end turn!");
        }
        $playerId = $gameEntity->getActivePlayerId() + 1;
        $maxPlayerCount = $gameEntity->getMaxActivePlayers();
        if ($playerId > $maxPlayerCount) {
            $playerId -= $maxPlayerCount;
        }
        $gameEntity->setActivePlayerId($playerId);
        $gameEntity->setAllowedToEndTurn(0);
        $gameEntity->setRolledDice(0);
        $em->persist($gameEntity);
        $playerEntity = PlayerFactory::getActivePlayer($em, $this)->getPlayerEntity();
        $streets = StreetFactory::getAllByPlayerId($em, $playerEntity->getId());
        $streetNames = array_map(function (Street $street) {
            return $street->getSimpleInfo();
        }, $streets);
        return [
            "money" => $playerEntity->getMoney(),
            "playerId" => $playerEntity->getId(),
            "position" => $playerEntity->getPosition(),
            "gameId" => $playerEntity->getGameId(),
            "ingameId" => $playerEntity->getIngameId(),
            "sessionId" => $gameEntity->getSessionId(),
            'streets' => implode('<br>---------------<br>', $streetNames),
            "success" => true,
            "inJail" => $playerEntity->getIsInJail(),
            "canRollForEscapes" => $playerEntity->getCanRollForEscape()
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
        $this->gameEntity->setRolledDice(0);
        $this->gameEntity->setPaschCount(0);
        $this->gameEntity->setGameOver(0);

        $em->persist($this->gameEntity);

    }
}