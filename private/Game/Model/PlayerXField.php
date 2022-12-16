<?php

namespace GameOfThronesMonopoly\Game\Model;

use Cassandra\RetryPolicy\Logging;
use GameOfThronesMonopoly\Game\Entities\player_x_field;

class PlayerXField
{
    private $playerXFieldEntity;

    /**
     * @param $playerXFieldEntity
     */
    public function __construct($playerXFieldEntity = null)
    {
        $this->playerXFieldEntity = $playerXFieldEntity;
    }

    /**
     * @return mixed|null
     */
    public function getPlayerXFieldEntity(): mixed
    {
        return $this->playerXFieldEntity;
    }

    /**
     * Create a new playerXField Entity
     * @author Fabian Müller
     * @param $em
     * @param $playerId
     * @param $fieldId
     * @return void
     */
    public function create($em, $playerId, $fieldId){
        $this->playerXFieldEntity = new player_x_field();
        $this->playerXFieldEntity->setBuildings(0);
        $this->playerXFieldEntity->setPlayerId($playerId);
        $this->playerXFieldEntity->setFieldId($fieldId);
        $em->persist($this->playerXFieldEntity);
    }
}