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

    /**
     * Buy the street that the player is currently on
     * @author Fabian Müller
     * @param $em
     * @return bool
     */
    public function buyStreet($em){
        $this->em = $em;
        return $this->checkFunds();
    }

    /**
     * Check if the player has enough money to buy the street
     * @author Fabian Müller
     * @return bool
     */
    public function checkFunds(){
        $street = StreetFactory::filterOne($this->em, array(
            array('playfieldId', 'equal', $this->playerEntity->getPosition())
        ));
        if($street->getStreetEntity()->getStreetCosts() > $this->playerEntity->getMoney()){
                return false;
        };
        $this->payMoney($street->getStreetEntity()->getStreetCosts());
        return true;
    }

    /**
     * Pay money
     * @author Fabian Müller
     * @param $amount
     * @return void
     */
    public function payMoney($amount){
        $cash = $this->playerEntity->getMoney()-$amount;
        $this->playerEntity->setMoney($cash);
        $this->em->persist($this->playerEntity);
    }

    public function sellStreet($id){
        $street = StreetFactory::filterOne($this->em, array(
            array('playfieldId', 'equal', $id)
        ));
        $this->payMoney(-($street->getStreetEntity()->getStreetCosts()/2));
    }
}