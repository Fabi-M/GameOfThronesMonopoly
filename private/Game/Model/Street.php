<?php

namespace GameOfThronesMonopoly\Game\Model;

use GameOfThronesMonopoly\Game\Entities\street as streetEntity;

class Street
{
    private $streetEntity;
    private $xField;

    /**
     * @param streetEntity  $streetEntity
     * @param ?PlayerXField $playerXField
     */
    public function __construct(streetEntity $streetEntity, ?PlayerXField $playerXField)
    {
        $this->streetEntity = $streetEntity;
        $this->xField = $playerXField;
    }

    public function getStreetEntity(): streetEntity
    {
        return $this->streetEntity;
    }

    /**
     * @return ?PlayerXField
     */
    public function getXField(): ?PlayerXField
    {
        return $this->xField;
    }

    /**
     * @return mixed
     * @author Selina Stöcklein
     */
    public function getRent(): mixed
    {
        $houses = $this->xField->getPlayerXFieldEntity()->getBuildings();
        $getRent = 'getBuildingRent' . $houses;
        return $this->streetEntity->$getRent();
    }

    /**
     * @return bool
     * @author Selina Stöcklein
     */
    public function isUnOwned(): bool
    {
        return is_null($this->xField);
    }
}