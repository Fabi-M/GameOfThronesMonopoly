<?php

namespace GameOfThronesMonopoly\Game\Entities;

use GameOfThronesMonopoly\Core\Datamapper\BaseEntity;

/**
 * Entity of the game
 * @primaryKey id
 * @Repository
 */
class Game extends BaseEntity
{
    protected $id;
    protected $sessionId;
    protected $activePlayerId;
    protected $maxActivePlayers;
    protected $allowedToEndTurn;
    protected $rolledDice;
    protected $gameOver;

    /**
     * @return mixed
     */
    public function getGameOver()
    {
        return $this->gameOver;
    }

    /**
     * @param mixed $gameOver
     */
    public function setGameOver($gameOver): void
    {
        $this->gameOver = $gameOver;
    }

    /**
     * @return mixed
     */
    public function getRolledDice()
    {
        return $this->rolledDice;
    }

    /**
     * @param mixed $rolledDice
     */
    public function setRolledDice($rolledDice): void
    {
        $this->rolledDice = $rolledDice;
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
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * @param mixed $sessionId
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
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
     */
    public function setActivePlayerId($activePlayerId)
    {
        $this->activePlayerId = $activePlayerId;
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
     */
    public function setMaxActivePlayers($maxActivePlayers)
    {
        $this->maxActivePlayers = $maxActivePlayers;
    }

    /**
     * @return mixed
     */
    public function getAllowedToEndTurn()
    {
        return $this->allowedToEndTurn;
    }

    /**
     * @param mixed $allowedToEndTurn
     */
    public function setAllowedToEndTurn($allowedToEndTurn)
    {
        $this->allowedToEndTurn = $allowedToEndTurn;
    }

    /**
     * @return mixed
     */
    public function getPaschCount()
    {
        return $this->paschCount;
    }

    /**
     * @param mixed $paschCount
     */
    public function setPaschCount($paschCount): void
    {
        $this->paschCount = $paschCount;
    }

}