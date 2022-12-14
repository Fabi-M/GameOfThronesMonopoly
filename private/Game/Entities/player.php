<?php

namespace GameOfThronesMonopoly\Game\Entities;

/**
 * Entity of the player
 * @primaryKey id
 * @Repository player
 */
class player
{
    protected int $id;
    protected int $gameId;
    protected int $ingameId;
    protected int $money;
    protected int $position;
    protected string $sessionId;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return player
     */
    public function setId(int $id): player
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getGameId(): int
    {
        return $this->gameId;
    }

    /**
     * @param int $gameId
     * @return player
     */
    public function setGameId(int $gameId): player
    {
        $this->gameId = $gameId;
        return $this;
    }

    /**
     * @return int
     */
    public function getIngameId(): int
    {
        return $this->ingameId;
    }

    /**
     * @param int $ingameId
     * @return player
     */
    public function setIngameId(int $ingameId): player
    {
        $this->ingameId = $ingameId;
        return $this;
    }

    /**
     * @return int
     */
    public function getMoney(): int
    {
        return $this->money;
    }

    /**
     * @param int $money
     * @return player
     */
    public function setMoney(int $money): player
    {
        $this->money = $money;
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return player
     */
    public function setPosition(int $position): player
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * @param string $sessionId
     * @return player
     */
    public function setSessionId(string $sessionId): player
    {
        $this->sessionId = $sessionId;
        return $this;
    }
}