<?php

namespace GameOfThronesMonopoly\Game\Service;

use Exception;
use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Core\Exceptions\SQLException;
use GameOfThronesMonopoly\Game\Factories\PlayerFactory;
use GameOfThronesMonopoly\Game\Factories\PlayerXFieldFactory;
use GameOfThronesMonopoly\Game\Factories\PlayFieldFactory;
use GameOfThronesMonopoly\Game\Factories\SpecialFieldFactory;
use GameOfThronesMonopoly\Game\Factories\StreetFactory;
use GameOfThronesMonopoly\Game\Model\Game;
use GameOfThronesMonopoly\Game\Model\Player;
use GameOfThronesMonopoly\Game\Model\PlayerXField;
use GameOfThronesMonopoly\Game\Model\Street;
use GameOfThronesMonopoly\Game\Repositories\StreetRepository;
use ReflectionException;

class StreetService
{
    private ?Game $game;
    private ?Player $player;
    private ?Street $street;
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
     * @author Fabian Müller
     */
    public function checkIfBuyable(): bool
    {
        $playerXStreet = PlayerXFieldFactory::getByFieldId(
            $this->em, $this->game->getGameEntity()->getId(), $this->player->getPlayerEntity()->getPosition()
        );
        $street = PlayFieldFactory::getPlayField(
            $this->em,
            $this->player->getPlayerEntity()->getPosition(),
            null
        );
        //TODO 21.12.2022 Selina: Bahnhöfe, Energiewerk, Wasserwerk
        if (!($street instanceof Street)) {
            return false;
        }
        return !$playerXStreet;
    }

    /**
     * Buy the street that the player is currently standing on
     * @return bool
     * @throws Exception
     * @author Fabian Müller
     */
    public function buyStreet(): bool|array
    {
        if(!((bool) $this->game->getGameEntity()->getAllowedToEndTurn())) return false; // player has to roll first

        $this->player = PlayerFactory::getActivePlayer($this->em, $this->game);
        //$this->player->getPlayerEntity()->setPosition(24); // todo nur zum testen, position wird über würfeln gesetzt

        if(!$this->checkIfBuyable()) return false; // street is already owned by another player
        if(!$this->player->buyStreet($this->em)) return false; // doesn't have enough money

        $playerXField = new PlayerXField();
        $playerXField->create(
            $this->em,
            $this->player->getPlayerEntity()->getId(),
            $this->player->getPlayerEntity()->getPosition(),
            $this->game->getGameEntity()->getId()
        );

        return [
            "streetName" => $this->street->getStreetEntity()->getName(),
            "playerId" => $this->player->getPlayerEntity()->getId(),
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
        if(!((bool) $this->game->getGameEntity()->getAllowedToEndTurn())) return false; // player has to roll first

        $this->getAllModels($fieldId);

        if($this->playerXField == null) return false; // doesn't own street
        if($this->playerXField->getPlayerXFieldEntity()->getBuildings() > 0) return false; // has houses on street, needs to sell them first
        // todo: check if player has houses on other streets with the same color -> can't sell street

        $this->playerXField->delete($this->em);
        $this->player->sellStreet($fieldId);

        return [
            "streetName" => $this->street->getStreetEntity()->getName(),
            "playerId" => $this->player->getPlayerEntity()->getId(),
            "success" => true,
            "totalMoney" => $this->player->getPlayerEntity()->getMoney()
        ];
    }

    /**
     * Buy a house on the selected street
     * @param $fieldId
     * @return bool|array
     * @throws SQLException
     * @throws Exception
     * @author Fabian Müller
     */
    public function buyHouse($fieldId): bool|array
    {
        if(!((bool) $this->game->getGameEntity()->getAllowedToEndTurn())) return false; // player has to roll first

        $this->getAllModels($fieldId);

        if($this->playerXField == null) return false; // street not owned
        if($this->playerXField->getPlayerXFieldEntity()->getBuildings() >= 5) return false; // already max housed build
        if(!$this->checkIfFullStreet()) return false; // doesn't own all streets of color

        $this->player->changeBalance(-($this->street->getStreetEntity()->getBuildingCosts()));
        $this->playerXField->getPlayerXFieldEntity()->setBuildings($this->playerXField->getPlayerXFieldEntity()->getBuildings()+1);
        $this->em->persist($this->playerXField->getPlayerXFieldEntity());

        return [
            "streetName" => $this->street->getStreetEntity()->getName(),
            "playerId" => $this->player->getPlayerEntity()->getId(),
            "success" => true,
            "totalMoney" => $this->player->getPlayerEntity()->getMoney(),
            "buildings" => $this->playerXField->getPlayerXFieldEntity()->getBuildings()
        ];
    }

    /**
     * Sell a house on the selected street
     * @param $fieldId
     * @return bool
     * @throws Exception
     * @author Fabian Müller
     */
    public function sellHouse($fieldId): bool|array
    {
        if(!((bool) $this->game->getGameEntity()->getAllowedToEndTurn())) return false; // player has to roll first

        $this->getAllModels($fieldId);

        if($this->playerXField == null) return false; // street not owned
        if($this->playerXField->getPlayerXFieldEntity()->getBuildings() == 0) return false; // no buildings on street

        $this->player->changeBalance($this->street->getStreetEntity()->getBuildingCosts()/2);
        $this->playerXField->getPlayerXFieldEntity()->setBuildings($this->playerXField->getPlayerXFieldEntity()->getBuildings()-1);
        $this->em->persist($this->playerXField->getPlayerXFieldEntity());

        return [
            "streetName" => $this->street->getStreetEntity()->getName(),
            "playerId" => $this->player->getPlayerEntity()->getId(),
            "success" => true,
            "totalMoney" => $this->player->getPlayerEntity()->getMoney(),
            "buildings" => $this->playerXField->getPlayerXFieldEntity()->getBuildings()
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
        $this->player = PlayerFactory::getActivePlayer($this->em, $this->game);
        $this->player->setEm($this->em);
        $this->street = StreetFactory::getByFieldId($this->em, $fieldId);
        $this->playerXField = PlayerXFieldFactory::getByFieldId($this->em, $this->game->getGameEntity()->getId(), $fieldId);

    }
}