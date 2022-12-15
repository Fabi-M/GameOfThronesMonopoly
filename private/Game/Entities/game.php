<?php

namespace GameOfThronesMonopoly\Game\Entities;

use GameOfThronesMonopoly\Core\Datamapper\BaseEntity;

/**
 * Entity of the game
 * @primaryKey id
 * @Repository
 */
class game extends BaseEntity
{
    protected $id;
    protected $sessionId;
    protected $activePlayerId;
    protected $maxActivePlayers;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return game
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return game
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActivePlayerId()
    {
        return $this->activePlayerId;
    }

    /**
     * @param mixed $activePlayerId
     * @return game
     */
    public function setActivePlayerId($activePlayerId)
    {
        $this->activePlayerId = $activePlayerId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaxActivePlayers()
    {
        return $this->maxActivePlayers;
    }

    /**
     * @param mixed $maxActivePlayers
     * @return game
     */
    public function setMaxActivePlayers($maxActivePlayers)
    {
        $this->maxActivePlayers = $maxActivePlayers;
        return $this;
    }


}