<?php

namespace GameOfThronesMonopoly\Game\Entities;

use GameOfThronesMonopoly\Core\Datamapper\BaseEntity;

/**
 * Entity of player X field
 * @primaryKey id
 * @Repository
 */
class player_x_street extends BaseEntity
{
    protected $id;
    protected $playerId;
    protected $buildings;
    protected $mortgage;
    protected $streetId;
    protected $gameId;

    /**
     * @return mixed
     */
    public function getStreetId()
    {
        return $this->streetId;
    }

    /**
     * @param mixed $streetId
     */
    public function setStreetId($streetId): void
    {
        $this->streetId = $streetId;
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
     */
    public function setId($id)
    {
        $this->id = $id;
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
     */
    public function setPlayerId($playerId)
    {
        $this->playerId = $playerId;
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
     */
    public function setBuildings($buildings)
    {
        $this->buildings = $buildings;
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
     */
    public function setMortgage($mortgage)
    {
        $this->mortgage = $mortgage;
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
     */
    public function setGameId($gameId)
    {
        $this->gameId = $gameId;
    }
}