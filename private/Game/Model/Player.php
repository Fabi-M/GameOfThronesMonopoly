<?php

namespace GameOfThronesMonopoly\Game\Model;

use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Factories\StreetFactory;
use GameOfThronesMonopoly\Game\Service\SpecialFieldService;
use ReflectionException;

class Player
{
    private $playerEntity;

    /**
     * @return \GameOfThronesMonopoly\Game\Entities\Player
     */
    public function getPlayerEntity(): \GameOfThronesMonopoly\Game\Entities\Player
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
     * @param               $playerId
     * @param               $gameId
     * @return void
     * @author Fabian Müller
     */
    public function create(EntityManager $em, $playerId, $gameId): void
    {
        $playerEntity = new \GameOfThronesMonopoly\Game\Entities\Player();
        $playerEntity->setGameId($gameId);
        $playerEntity->setIngameId($playerId);
        $playerEntity->setPosition(0);
        $playerEntity->setMoney(1500);
        $playerEntity->setIsInJail(0);
        $playerEntity->setJailRolls(0);
        $playerEntity->setCanRollForEscape(1);
        $em->persist($playerEntity);
    }

    /**
     * @param EntityManager $em
     * @param array         $rolled
     * @return void
     * @author Selina Stöcklein
     */
    public function move(EntityManager $em, array $rolled, int $overridePosition = 0)
    {
        $newPosition = 0;
        if($overridePosition != 0){
            $newPosition = $overridePosition;
        }else{
            $oldPosition = $this->getPlayerEntity()->getPosition();
            // 0-39 felder
            $newPosition = $oldPosition + array_sum($rolled);
            $newPosition = $newPosition <= Game::MAX_PLAY_FIELDS ? $newPosition : $newPosition - 40;
        }
        $this->getPlayerEntity()->setPosition($newPosition);
        $specialFieldService = new SpecialFieldService();
        $speciaFieldReturn = $specialFieldService->checkIfOnSpecialField($em, $newPosition, $this);
        $em->persist($this->getPlayerEntity());
        $speciaFieldReturn ["playFieldId"] = $newPosition;
        return $speciaFieldReturn;
    }

    /**
     * Buy the street that the player is currently on
     * @param EntityManager $em
     * @return bool
     * @throws ReflectionException
     * @author Fabian Müller
     */
    public function buyStreet(EntityManager $em): bool
    {
        return $this->checkFunds($em);
    }

    /**
     * Check if the player has enough money to buy the street
     * @param EntityManager $em
     * @return bool
     * @throws ReflectionException
     * @author Fabian Müller
     */
    public function checkFunds(EntityManager $em): bool
    {
        $street = StreetFactory::filterOne($em, [
            ['playfieldId', 'equal', $this->playerEntity->getPosition()]
        ]);
        if ($street->getStreetEntity()->getStreetCosts() > $this->playerEntity->getMoney()) {
            return false;
        }
        $this->changeBalance(-($street->getStreetEntity()->getStreetCosts()),$em);
        return true;
    }

    /**
     * Pay money
     * @param               $amount
     * @param EntityManager $em
     * @return void
     * @author Fabian Müller
     */
    public function changeBalance($amount, EntityManager $em): void
    {
        $cash = $this->playerEntity->getMoney() + $amount;
        $this->playerEntity->setMoney($cash);
        $em->persist($this->playerEntity);
    }

    /**
     * @param               $id
     * @param EntityManager $em
     * @return void
     * @throws ReflectionException
     * @author Fabian Müller
     */
    public function sellStreet($id, EntityManager $em): void
    {
        $street = StreetFactory::filterOne($em, [
            ['playfieldId', 'equal', $id]
        ]);
        $this->changeBalance($street->getStreetEntity()->getStreetCosts() / 2, $em);
    }

    /**
     * @return bool
     */
    public function isGameOver(): bool
    {
        return $this->getPlayerEntity()->getMoney() < 0;
    }

    /**
     * @param Player        $owner
     * @param Street        $street
     * @param EntityManager $em
     * @return int
     * @author Selina Stöcklein
     */
    public function payRentTo(Player $owner, Street $street, EntityManager $em): int
    {
        $rent = $street->getRent(); // 0 oder mehr
        $this->changeBalance(-($rent), $em);
        $owner->changeBalance($rent, $em);
        return $rent;
    }

    /**
     * Send player to jail
     * @param EntityManager $em
     * @return void
     * @author Fabian Müller
     */
    public function goToJail(EntityManager $em){
        $this->getPlayerEntity()->setPosition(10);
        $this->getPlayerEntity()->setIsInJail(1);
        $em->persist($this->getPlayerEntity());
    }

    /**
     * Check if the player has escaped jail
     * @param $rolled
     * @return void
     * @author Fabian Müller
     */
    public function hasRolledForEscape($rolled){
        if($this->getPlayerEntity()->getJailRolls()+1 == 3){
            $this->getPlayerEntity()->setCanRollForEscape(0);
        }
        if($rolled[0] == $rolled[1]){
            $this->getPlayerEntity()->setIsInJail(0);
        }else{
            $rolls = $this->getPlayerEntity()->getJailRolls() + 1;
            $this->getPlayerEntity()->setJailRolls($rolls);
        }
    }

    /**
     * @param $em
     * @return void
     * @author Fabian Müller
     */
    public function jailBuyout($em){
        $this->changeBalance(-50, $em);
        $this->getPlayerEntity()->setJailRolls(0);
        $this->getPlayerEntity()->setCanRollForEscape(1);
        $this->getPlayerEntity()->setIsInJail(0);
        $em->persist($this->getPlayerEntity());
    }
}