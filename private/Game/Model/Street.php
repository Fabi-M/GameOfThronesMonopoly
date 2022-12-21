<?php

namespace GameOfThronesMonopoly\Game\Model;

use GameOfThronesMonopoly\Game\Entities\street as streetEntity;

class Street
{
    private streetEntity $streetEntity;
    private ?PlayerXField $xField;

    /**
     * @param streetEntity $streetEntity
     * @param PlayerXField|null $playerXField
     */
    public function __construct(streetEntity $streetEntity, PlayerXField $playerXField = null)
    {
        $this->streetEntity = $streetEntity;
        $this->xField = $playerXField;
    }

    public function getStreetEntity(): streetEntity
    {
        return $this->streetEntity;
    }

    /**
     * @return PlayerXField
     */
    public function getXField(): PlayerXField
    {
        return $this->xField;
    }

    public function getRent()
    {
        $houses = $this->xField->getPlayerXFieldEntity()->getBuildings();
        $getRent= 'getBuildingRent'.$houses;
        return $this->streetEntity->$getRent();
    }
}