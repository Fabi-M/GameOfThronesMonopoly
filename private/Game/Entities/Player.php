<?php

namespace GameOfThronesMonopoly\Game\Entities;

class Player
{
    private int $id;
    private int $game_id;
    private int $ingame_id;
    private int $money;
    private int $position;
    private string $session_id;

    /**
     * @param int $id
     * @param int $game_id
     * @param int $ingame_id
     * @param int $money
     * @param int $position
     * @param string $session_id
     */
    public function __construct(int $id, int $game_id, int $ingame_id, int $money, int $position, string $session_id)
    {
        $this->id = $id;
        $this->game_id = $game_id;
        $this->ingame_id = $ingame_id;
        $this->money = $money;
        $this->position = $position;
        $this->session_id = $session_id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Player
     */
    public function setId(int $id): Player
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getGameId(): int
    {
        return $this->game_id;
    }

    /**
     * @param int $game_id
     * @return Player
     */
    public function setGameId(int $game_id): Player
    {
        $this->game_id = $game_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getIngameId(): int
    {
        return $this->ingame_id;
    }

    /**
     * @param int $ingame_id
     * @return Player
     */
    public function setIngameId(int $ingame_id): Player
    {
        $this->ingame_id = $ingame_id;
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
     * @return Player
     */
    public function setMoney(int $money): Player
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
     * @return Player
     */
    public function setPosition(int $position): Player
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->session_id;
    }

    /**
     * @param string $session_id
     * @return Player
     */
    public function setSessionId(string $session_id): Player
    {
        $this->session_id = $session_id;
        return $this;
    }
}