<?php

namespace GameOfThronesMonopoly\Game\Model;

use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Factories\StreetFactory;

class Player
{
    private $playerEntity;
    private $em;

    /**
     * @param mixed $em
     * @return Player
     */
    public function setEm($em)
    {
        $this->em = $em;
        return $this;
    }

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
     * @param EntityManager $em
     * @param array         $rolled
     * @return void
     * @author Selina Stöcklein
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

    /**
     * Buy the street that the player is currently on
     * @param $em
     * @return bool
     * @author Fabian Müller
     */
    public function buyStreet($em)
    {
        $this->em = $em;
        return $this->checkFunds();
    }

    /**
     * Check if the player has enough money to buy the street
     * @return bool
     * @author Fabian Müller
     */
    public function checkFunds()
    {
        var_dump($this->playerEntity->getPosition());
        $street = StreetFactory::filterOne($this->em, [
            ['playfieldId', 'equal', $this->playerEntity->getPosition()]
        ]);
        var_dump($street);
        if ($street->getStreetEntity()->getStreetCosts() > $this->playerEntity->getMoney()) {
            return false;
        };
        $this->payMoney($street->getStreetEntity()->getStreetCosts());
        return true;
    }

    /**
     * Pay money
     * @param $amount
     * @return void
     * @author Fabian Müller
     */
    public function payMoney($amount)
    {
        $cash = $this->playerEntity->getMoney() - $amount;
        $this->playerEntity->setMoney($cash);
        $this->em->persist($this->playerEntity);
    }

    public function sellStreet($id)
    {
        $street = StreetFactory::filterOne($this->em, [
            ['playfieldId', 'equal', $id]
        ]);
        $this->payMoney(-($street->getStreetEntity()->getStreetCosts() / 2));
    }
}