<?php

namespace GameOfThronesMonopoly\Game\Entities;

use GameOfThronesMonopoly\Core\Datamapper\BaseEntity;

/**
 * Entity of the player
 * @primaryKey id
 * @Repository
 */
class player extends BaseEntity
{
    protected $id;
    protected $gameId;
    protected $ingameId;
    protected $money;
    protected $position;
    protected $sessionId;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return player
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return player
     */
    public function setGameId($gameId)
    {
        $this->gameId = $gameId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIngameId()
    {
        return $this->ingameId;
    }

    /**
     * @param mixed $ingameId
     * @return player
     */
    public function setIngameId($ingameId)
    {
        $this->ingameId = $ingameId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMoney()
    {
        return $this->money;
    }

    /**
     * @param mixed $money
     * @return player
     */
    public function setMoney($money)
    {
        $this->money = $money;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     * @return player
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * @param mixed $sessionId
     * @return player
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
        return $this;
    }


}