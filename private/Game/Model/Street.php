<?php

namespace GameOfThronesMonopoly\Game\Model;

use GameOfThronesMonopoly\Core\Datamapper\EntityManager;

class Street
{
    private \GameOfThronesMonopoly\Game\Entities\street $streetEntity;

    /**
     * @param $streetEntity
     */
    public function __construct(\GameOfThronesMonopoly\Game\Entities\street $streetEntity)
    {
        $this->streetEntity = $streetEntity;
    }

    public function getStreetEntity()
    {
        return $this->streetEntity;
    }

    /**
     * @param $streetEntity
     * @return
     */
    public function setStreetEntity(\GameOfThronesMonopoly\Game\Entities\street $streetEntity)
    {
        $this->streetEntity = $streetEntity;
        return $this;
    }
}