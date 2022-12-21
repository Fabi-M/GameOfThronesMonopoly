<?php

namespace GameOfThronesMonopoly\Game\Model;

use GameOfThronesMonopoly\Game\Entities\player_x_field;

class PlayerXField
{
    private ?player_x_field $playerXFieldEntity;

    /**
     * @param player_x_field|null $playerXFieldEntity
     */
    public function __construct(?player_x_field $playerXFieldEntity = null)
    {
        $this->playerXFieldEntity = $playerXFieldEntity;
    }

    /**
     * @return player_x_field
     */
    public function getPlayerXFieldEntity(): player_x_field
    {
        return $this->playerXFieldEntity;
    }

    /**
     * Create a new playerXField Entity
     * @param $em
     * @param $playerId
     * @param $fieldId
     * @param $gameId
     * @return void
     * @author Fabian Müller
     */
    public function create($em, $playerId, $fieldId, $gameId)
    {
        $this->playerXFieldEntity = new player_x_field();
        $this->playerXFieldEntity->setBuildings(0);
        $this->playerXFieldEntity->setPlayerId($playerId);
        $this->playerXFieldEntity->setFieldId($fieldId);
        $this->playerXFieldEntity->setMortgage(0);
        $this->playerXFieldEntity->setGameId($gameId);
        $em->persist($this->playerXFieldEntity);
    }

    public function delete($em)
    {
        $em->remove($this->playerXFieldEntity);
    }
}