<?php

namespace GameOfThronesMonopoly\Game\Entities;

use GameOfThronesMonopoly\Core\Datamapper\BaseEntity;

/**
 * Entity of the user
 * @Primary id;
 * @Repository
 */
class game extends BaseEntity
{
    private int $id;
    private string $session_id;
    private int $active_player_id;
    private int $max_player_id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Game
     */
    public function setId(int $id): Game
    {
        $this->id = $id;
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
     * @return Game
     */
    public function setSessionId(string $session_id): Game
    {
        $this->session_id = $session_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getActivePlayerId(): int
    {
        return $this->active_player_id;
    }

    /**
     * @param int $active_player_id
     * @return Game
     */
    public function setActivePlayerId(int $active_player_id): Game
    {
        $this->active_player_id = $active_player_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxPlayerId(): int
    {
        return $this->max_player_id;
    }

    /**
     * @param int $max_player_id
     * @return Game
     */
    public function setMaxPlayerId(int $max_player_id): Game
    {
        $this->max_player_id = $max_player_id;
        return $this;
    }
}