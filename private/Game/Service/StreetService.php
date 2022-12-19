<?php

namespace GameOfThronesMonopoly\Game\Service;

use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Factories\PlayerFactory;
use GameOfThronesMonopoly\Game\Factories\PlayerXFieldFactory;
use GameOfThronesMonopoly\Game\Factories\StreetFactory;
use GameOfThronesMonopoly\Game\Model\Game;
use GameOfThronesMonopoly\Game\Model\Player;
use GameOfThronesMonopoly\Game\Model\PlayerXField;

class StreetService
{
    private Game $game;
    private Player $player;
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
        $playerXStreet = PlayerXFieldFactory::filterOne($this->em, array(
                array('playerId', 'equal', $this->player->getPlayerEntity()->getId()),
                array('fieldId', 'equal', $this->player->getPlayerEntity()->getPosition())
            )
        );
        return !$playerXStreet;
    }

    /**
     * Buy the street that the player is currently standing on
     * @author Fabian Müller
     * @return bool
     */
    public function buyStreet()
    {
        $this->player = PlayerFactory::filterOne($this->em, array(
                array('sessionId', 'equal', $this->game->getGameEntity()->getSessionId()),
                array('ingameId', 'equal', $this->game->getGameEntity()->getActivePlayerId())
            )
        );
        $this->player->getPlayerEntity()->setPosition(24);
        if(!$this->checkIfBuyable()) return false;
        if(!$this->player->buyStreet($this->em)) return false;
        $playerXField = new PlayerXField();
        $playerXField->create($this->em, $this->player->getPlayerEntity()->getId(), $this->player->getPlayerEntity()->getPosition());
        return true;
    }

    public function sellStreet($id){
        $this->player = PlayerFactory::filterOne($this->em, array(
                array('sessionId', 'equal', $this->game->getGameEntity()->getSessionId()),
                array('ingameId', 'equal', $this->game->getGameEntity()->getActivePlayerId())
            )
        );
        $this->player->setEm($this->em);
        $playerXField = PlayerXFieldFactory::filterOne($this->em, array(
            array('playerId', 'equal', $this->player->getPlayerEntity()->getId()),
            array('fieldId', 'equal', $id)
        ));
        if($playerXField == null) return false;
        if($playerXField->getPlayerXFieldEntity()->getBuildings() > 0) return false;
        $playerXField->delete($this->em);
        $this->player->sellStreet($id);
        return true;
    }
}