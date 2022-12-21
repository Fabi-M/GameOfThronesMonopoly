<?php

namespace GameOfThronesMonopoly\Game\Service;

use Exception;
use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Core\Exceptions\SQLException;
use GameOfThronesMonopoly\Game\Factories\PlayerFactory;
use GameOfThronesMonopoly\Game\Factories\PlayerXFieldFactory;
use GameOfThronesMonopoly\Game\Factories\StreetFactory;
use GameOfThronesMonopoly\Game\Model\Game;
use GameOfThronesMonopoly\Game\Model\Player;
use GameOfThronesMonopoly\Game\Model\PlayerXField;
use GameOfThronesMonopoly\Game\Repositories\StreetRepository;

class StreetService
{
    private Game $game;
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
     * @author Fabian Müller
     * @return bool
     */
    public function checkIfBuyable()
    {
        $playerXStreet = PlayerXFieldFactory::getByFieldId($this->em, $this->player->getPlayerEntity()->getId(), $this->player->getPlayerEntity()->getPosition());
        return !$playerXStreet;
    }

    /**
     * Buy the street that the player is currently standing on
     * @return bool
     * @throws Exception
     * @author Fabian Müller
     */
    public function buyStreet()
    {
        $this->player = PlayerFactory::getActivePlayer($this->em, $this->game);
        //$this->player->getPlayerEntity()->setPosition(24); // todo nur zum testen, position wird über würfeln gesetzt

        if(!$this->checkIfBuyable()) return false; // street is already owned by another player
        if(!$this->player->buyStreet($this->em)) return false; // doesn't have enough money

        $playerXField = new PlayerXField();
        $playerXField->create($this->em, $this->player->getPlayerEntity()->getId(), $this->player->getPlayerEntity()->getPosition());

        return true;
    }

    /**
     * Sell the selected street
     * @param $fieldId
     * @return bool
     * @throws Exception
     * @author Fabian Müller
     */
    public function sellStreet($fieldId){
        $this->getAllModels($fieldId);

        if($this->playerXField == null) return false; // doesn't own street
        if($this->playerXField->getPlayerXFieldEntity()->getBuildings() > 0) return false; // has houses on street, needs to sell them first
        // todo: check if player has houses on other streets with the same color -> can't sell street

        $this->playerXField->delete($this->em);
        $this->player->sellStreet($fieldId);

        return true;
    }

    /**
     * Buy a house on the selected street
     * @author Fabian Müller
     * @param $fieldId
     * @return bool
     * @throws SQLException
     */
    public function buyHouse($fieldId){
        $this->getAllModels($fieldId);

        if($this->playerXField == null) return false; // street not owned
        if($this->playerXField->getPlayerXFieldEntity()->getBuildings() >= 5) return false; // already max housed build
        if(!$this->checkIfFullStreet()) return false; // doesn't own all streets of color

        $this->player->payMoney($this->street->getStreetEntity()->getBuildingCosts());
        $this->playerXField->getPlayerXFieldEntity()->setBuildings($this->playerXField->getPlayerXFieldEntity()->getBuildings()+1);
        $this->em->persist($this->playerXField->getPlayerXFieldEntity());

        return true;
    }

    /**
     * Sell a house on the selected street
     * @param $fieldId
     * @return bool
     * @throws Exception
     * @author Fabian Müller
     */
    public function sellHouse($fieldId){
        $this->getAllModels($fieldId);

        if($this->playerXField == null) return false; // street not owned
        if($this->playerXField->getPlayerXFieldEntity()->getBuildings() == 0) return false; // no buildings on street

        $this->player->payMoney(-($this->street->getStreetEntity()->getBuildingCosts()/2));
        $this->playerXField->getPlayerXFieldEntity()->setBuildings($this->playerXField->getPlayerXFieldEntity()->getBuildings()-1);
        $this->em->persist($this->playerXField->getPlayerXFieldEntity());

        return true;
    }

    /**
     * Check if the player owns all streets of the specific color
     * @author Fabian Müller
     * @return mixed
     * @throws SQLException
     */
    public function checkIfFullStreet(){
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
        $this->player = PlayerFactory::getActivePlayer($this->em, $this->game);
        $this->player->setEm($this->em);
        $this->street = StreetFactory::getByFieldId($this->em, $fieldId);
        $this->playerXField = PlayerXFieldFactory::getByFieldId($this->em, $this->player->getPlayerEntity()->getId(), $fieldId);

    }
}