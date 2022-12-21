<?php

namespace GameOfThronesMonopoly\Game\Entities;

use GameOfThronesMonopoly\Core\Datamapper\BaseEntity;

/**
 * Entity of player X field
 * @primaryKey id
 * @Repository
 */
class player_x_field extends BaseEntity
{
    protected $id;
    protected $playerId;
    protected $buildings;
    protected $mortgage;
    protected $fieldId;
    protected $gameId;

    /**
     * @return mixed
     */
    public function getFieldId()
    {
        return $this->fieldId;
    }

    /**
     * @param mixed $fieldId
     * @return player_x_field
     */
    public function setFieldId($fieldId)
    {
        $this->fieldId = $fieldId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return player_x_field
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlayerId()
    {
        return $this->playerId;
    }

    /**
     * @param mixed $playerId
     * @return player_x_field
     */
    public function setPlayerId($playerId)
    {
        $this->playerId = $playerId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBuildings()
    {
        return $this->buildings;
    }

    /**
     * @param mixed $buildings
     * @return player_x_field
     */
    public function setBuildings($buildings)
    {
        $this->buildings = $buildings;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMortgage()
    {
        return $this->mortgage;
    }

    /**
     * @param mixed $mortgage
     * @return player_x_field
     */
    public function setMortgage($mortgage)
    {
        $this->mortgage = $mortgage;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGameId()
    {
        return $this->gameId;
    }

    /**
     * @param mixed $gameId
     * @return player_x_field
     */
    public function setGameId($gameId)
    {
        $this->gameId = $gameId;
        return $this;
    }


}