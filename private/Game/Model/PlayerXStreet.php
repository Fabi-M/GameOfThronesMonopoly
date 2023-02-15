<?php

namespace GameOfThronesMonopoly\Game\Model;

use GameOfThronesMonopoly\Game\Entities\player_x_street;

class PlayerXStreet
{
    private ?player_x_street $playerXStreetEntity;

    /**
     * @param player_x_street|null $playerXFieldEntity
     */
    public function __construct(?player_x_street $playerXFieldEntity = null)
    {
        $this->playerXStreetEntity = $playerXFieldEntity;
    }

    /**
     * @return player_x_street
     */
    public function getPlayerXStreetEntity(): player_x_street
    {
        return $this->playerXStreetEntity;
    }

    /**
     * Create a new playerXField Entity
     * @param $em
     * @param $playerId
     * @param $streetId
     * @param $gameId
     * @return void
     * @author Fabian Müller
     */
    public function create($em, $playerId, $streetId, $gameId)
    {
        $this->playerXStreetEntity = new player_x_street();
        $this->playerXStreetEntity->setBuildings(0);
        $this->playerXStreetEntity->setPlayerId($playerId);
        $this->playerXStreetEntity->setStreetId($streetId);
        $this->playerXStreetEntity->setMortgage(0);
        $this->playerXStreetEntity->setGameId($gameId);
        $em->persist($this->playerXStreetEntity);
    }

    public function delete($em)
    {
        $em->remove($this->playerXStreetEntity);
    }
}