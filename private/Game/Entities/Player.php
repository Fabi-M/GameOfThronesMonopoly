<?php

namespace GameOfThronesMonopoly\Game\Entities;

use GameOfThronesMonopoly\Core\Datamapper\BaseEntity;

/**
 * Entity of the player
 * @primaryKey id
 * @Repository
 */
class Player extends BaseEntity
{
    protected $id;
    protected $gameId;
    protected $ingameId;
    protected $money;
    protected $position;
    protected $isInJail;
    protected $jailRolls;

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

    /**
     * @return mixed
     */
    public function getIngameId()
    {
        return $this->ingameId;
    }

    /**
     * @param mixed $ingameId
     */
    public function setIngameId($ingameId)
    {
        $this->ingameId = $ingameId;
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
     */
    public function setMoney($money)
    {
        $this->money = $money;
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
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getIsInJail()
    {
        return $this->isInJail;
    }

    /**
     * @param mixed $isInJail
     */
    public function setIsInJail($isInJail): void
    {
        $this->isInJail = $isInJail;
    }

    /**
     * @return mixed
     */
    public function getJailRolls()
    {
        return $this->jailRolls;
    }

    /**
     * @param mixed $jailRolls
     */
    public function setJailRolls($jailRolls): void
    {
        $this->jailRolls = $jailRolls;
    }


}