<?php

namespace GameOfThronesMonopoly\Game\Service;

use Exception;
use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Core\Exceptions\SQLException;
use GameOfThronesMonopoly\Game\Factories\PlayerFactory;
use GameOfThronesMonopoly\Game\Factories\PlayerXStreetFactory;
use GameOfThronesMonopoly\Game\Factories\PlayFieldFactory;
use GameOfThronesMonopoly\Game\Factories\StreetFactory;
use GameOfThronesMonopoly\Game\Model\PlayerXStreet;
use GameOfThronesMonopoly\Game\Model\Street;
use GameOfThronesMonopoly\Game\Repositories\StreetRepository;
use ReflectionException;

class StreetService
{
    private $game;
    private $player;
    private $street;
    private $playerXField;
    private EntityManager $em;

    /**
     * @param $game
     * @param $em
     */
    public function __construct($game, $em)
    {
        $this->game = $game;
        $this->em = $em;
    }

    /**
     * Check if the street is buyable
     * @return bool
     * @throws ReflectionException
     * @throws Exception
     * @author Fabian Müller
     */
    public function checkIfBuyable(): bool
    {
        $playerXStreet = PlayerXStreetFactory::getByFieldId(
            $this->em, $this->game->getGameEntity()->getId(), $this->player->getPlayerEntity()->getPosition()
        );
        $street = PlayFieldFactory::getPlayField(
            $this->em,
            $this->player->getPlayerEntity()->getPosition(),
            null
        );
        if (!($street instanceof Street)) {
            throw new Exception("Not a street");
        }
        return !$playerXStreet;
    }

    /**
     * Buy the street that the player is currently standing on
     * @return bool|array
     * @throws ReflectionException
     * @throws SQLException
     * @author Fabian Müller
     */
    public function buyStreet(): bool|array
    {
        if(!((bool) $this->game->getGameEntity()->getAllowedToEndTurn())) throw new Exception("Player has to roll first!"); // player has to roll first

        $this->player = PlayerFactory::getActivePlayer($this->em, $this->game);
        $playFieldId = $this->player->getPlayerEntity()->getPosition();

        if(!$this->checkIfBuyable()) throw new Exception("Street is already owned"); // street is already owned by another player
        if(!$this->player->buyStreet($this->em)) throw new Exception("Player doesn't have enough money"); // doesn't have enough money

        $playerXField = new PlayerXStreet();
        $playerXField->create(
            $this->em,
            $this->player->getPlayerEntity()->getId(),
            StreetRepository::getStreetIdFromPlayField($playFieldId),
            $this->game->getGameEntity()->getId()
        );
        $this->getAllModels($playFieldId);

        return [
            "streetName" => $this->street->getStreetEntity()->getName(),
            "playerId" => $this->player->getPlayerEntity()->getId(),
            "inGamePlayerId" => $this->player->getPlayerEntity()->getIngameId(),
            "playFieldId"=>$playFieldId,
            "success" => true,
            "totalMoney" => $this->player->getPlayerEntity()->getMoney()
            ]; // todo add response classes
    }

    /**
     * Sell the selected street
     * @param $fieldId
     * @return array|bool
     * @throws Exception
     * @author Fabian Müller
     */
    public function sellStreet($fieldId){

        if(!((bool) $this->game->getGameEntity()->getAllowedToEndTurn())) throw new Exception("Player has to roll first!"); // player has to roll first

        $this->getAllModels($fieldId);

        if($this->playerXField == null) throw new Exception("This street is currently not owned, you can't sell the street"); // doesn't own street
        if($this->playerXField->getPlayerXStreetEntity()->getBuildings() > 0) throw new Exception("You have to sell the houses before you can sell the street"); // has houses on street, needs to sell them first

        $this->playerXField->delete($this->em);
        $this->player->sellStreet($fieldId, $this->em);

        return [
            "streetName" => $this->street->getStreetEntity()->getName(),
            "playerId" => $this->player->getPlayerEntity()->getId(),
            "inGamePlayerId" => $this->player->getPlayerEntity()->getIngameId(),
            "playFieldId"=>$fieldId,
            "success" => true,
            "totalMoney" => $this->player->getPlayerEntity()->getMoney()
        ];
    }

    /**
     * Buy a house on the selected street
     * @param               $fieldId
     * @param EntityManager $em
     * @return bool|array
     * @throws SQLException
     * @author Fabian Müller
     */
    public function buyHouse($fieldId, EntityManager $em): bool|array
    {
        if(!((bool) $this->game->getGameEntity()->getAllowedToEndTurn())) { // player has to roll first
            throw new Exception("Player has to roll first!");
        }
        $this->getAllModels((int)$fieldId);

        if($this->playerXField == null) { // street not owned
            throw new Exception("This street is currently not owned, you can't buy houses");
        }
        if($this->playerXField->getPlayerXStreetEntity()->getBuildings() >= 5) { // already max housed build
            throw new Exception("There are already 5 buildings on the street");
        }
        if(!$this->checkIfFullStreet()) {  // doesn't own all streets of color
            throw new Exception("Player doesn't have all streets of color");
        }

        $this->player->changeBalance(-($this->street->getStreetEntity()->getBuildingCosts()), $em);
        $this->playerXField->getPlayerXStreetEntity()->setBuildings($this->playerXField->getPlayerXStreetEntity()->getBuildings()+1);
        $this->em->persist($this->playerXField->getPlayerXStreetEntity());

        return [
            "streetName" => $this->street->getStreetEntity()->getName(),
            "position" => $this->street->getStreetEntity()->getPlayFieldId(),
            "playerId" => $this->player->getPlayerEntity()->getId(),
            "success" => true,
            "totalMoney" => $this->player->getPlayerEntity()->getMoney(),
            "buildings" => $this->playerXField->getPlayerXStreetEntity()->getBuildings()
        ];
    }

    /**
     * Sell a house on the selected street
     * @param               $fieldId
     * @param EntityManager $em
     * @return bool|array
     * @throws Exception
     * @author Fabian Müller
     */
    public function sellHouse($fieldId, EntityManager $em): bool|array
    {
        if(!((bool) $this->game->getGameEntity()->getAllowedToEndTurn())) { // player has to roll first
            throw new Exception("Player has to roll first!");
        }

        $this->getAllModels($fieldId);

        if($this->playerXField == null) {  // street not owned
            throw new Exception("This street is currently not owned, there can't be any houses");
        }
        if($this->playerXField->getPlayerXStreetEntity()->getBuildings() == 0) {// no buildings on street
            throw new Exception("No buildings on this street");
        }

        $this->player->changeBalance($this->street->getStreetEntity()->getBuildingCosts()/2, $em);
        $this->playerXField->getPlayerXStreetEntity()->setBuildings($this->playerXField->getPlayerXStreetEntity()->getBuildings()-1);
        $this->em->persist($this->playerXField->getPlayerXStreetEntity());

        return [
            "streetName" => $this->street->getStreetEntity()->getName(),
            "playerId" => $this->player->getPlayerEntity()->getId(),
            "success" => true,
            "totalMoney" => $this->player->getPlayerEntity()->getMoney(),
            "buildings" => $this->playerXField->getPlayerXStreetEntity()->getBuildings(),
            "position" => $this->street->getStreetEntity()->getPlayFieldId()
        ];
    }

    /**
     * Check if the player owns all streets of the specific color
     * @author Fabian Müller
     * @return bool
     * @throws SQLException
     * @author Fabian Müller
     */
    public function checkIfFullStreet(): bool
    {
        return filter_var(StreetRepository::checkIfAllStreetsOwned(
            $this->street->getStreetEntity()->getColor(),
            $this->player->getPlayerEntity()->getId()),
            FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Get all needed models with needed entities by fieldId
     * @param $fieldId
     * @return void
     * @throws Exception
     * @author Fabian Müller
     */
    public function getAllModels($fieldId){
        if($this->player == null){
            $this->player = PlayerFactory::getActivePlayer($this->em, $this->game);
        }
        $this->street = StreetFactory::getByFieldId($this->em, $fieldId);
        $this->playerXField = PlayerXStreetFactory::getByFieldId($this->em, $this->game->getGameEntity()->getId(), $fieldId);
    }
}