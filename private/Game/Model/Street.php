<?php

namespace GameOfThronesMonopoly\Game\Model;

use GameOfThronesMonopoly\Game\Entities\street as streetEntity;

class Street
{
    private $streetEntity;
    private $xField;

    /**
     * @param streetEntity   $streetEntity
     * @param ?PlayerXStreet $playerXField
     */
    public function __construct(streetEntity $streetEntity, ?PlayerXStreet $playerXField)
    {
        $this->streetEntity = $streetEntity;
        $this->xField = $playerXField;
    }

    public function getStreetEntity(): streetEntity
    {
        return $this->streetEntity;
    }

    /**
     * @return ?PlayerXStreet
     */
    public function getXField(): ?PlayerXStreet
    {
        return $this->xField;
    }

    /**
     * @return mixed
     * @author Selina Stöcklein
     */
    public function getRent(): mixed
    {
        $houses = $this->xField->getPlayerXStreetEntity()->getBuildings();
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

    /**
     * auxiliary function for displaying some basic information
     * @return string
     */
    public function getSimpleInfo():string{
        return "Name: " . $this->getStreetEntity()->getName() .
        '<br>Miete: ' . $this->getRent() .
        '<br>Farbe: ' . ucfirst($this->getStreetEntity()->getColor());
    }
}