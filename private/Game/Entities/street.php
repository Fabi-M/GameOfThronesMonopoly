<?php

namespace GameOfThronesMonopoly\Game\Entities;

use GameOfThronesMonopoly\Core\Datamapper\BaseEntity;

/**
 * Entity of the street
 * @primaryKey id
 * @Repository
 */
class street extends BaseEntity
{
    protected int $id;
    protected int $playFieldId;
    protected string $name;
    protected int $streetCosts;
    protected string $color;
    protected int $buildingCosts;
    protected int $buildingRent0;
    protected int $buildingRent1;
    protected int $buildingRent2;
    protected int $buildingRent3;
    protected int $buildingRent4;
    protected int $buildingRent5;
    protected int $mortgage;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getStreetCosts()
    {
        return $this->streetCosts;
    }

    /**
     * @param mixed $streetCosts
     */
    public function setStreetCosts($streetCosts)
    {
        $this->streetCosts = $streetCosts;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return mixed
     */
    public function getBuildingCosts()
    {
        return $this->buildingCosts;
    }

    /**
     * @param mixed $buildingCosts
     */
    public function setBuildingCosts($buildingCosts)
    {
        $this->buildingCosts = $buildingCosts;
    }

    /**
     * @return int
     */
    public function getBuildingRent0(): int
    {
        return $this->buildingRent0;
    }

    /**
     * @param int $buildingRent0
     */
    public function setBuildingRent0(int $buildingRent0): void
    {
        $this->buildingRent0 = $buildingRent0;
    }

    /**
     * @return int
     */
    public function getBuildingRent1(): int
    {
        return $this->buildingRent1;
    }

    /**
     * @param int $buildingRent1
     */
    public function setBuildingRent1(int $buildingRent1): void
    {
        $this->buildingRent1 = $buildingRent1;
    }

    /**
     * @return int
     */
    public function getBuildingRent2(): int
    {
        return $this->buildingRent2;
    }

    /**
     * @param int $buildingRent2
     */
    public function setBuildingRent2(int $buildingRent2): void
    {
        $this->buildingRent2 = $buildingRent2;
    }

    /**
     * @return int
     */
    public function getBuildingRent3(): int
    {
        return $this->buildingRent3;
    }

    /**
     * @param int $buildingRent3
     */
    public function setBuildingRent3(int $buildingRent3): void
    {
        $this->buildingRent3 = $buildingRent3;
    }

    /**
     * @return int
     */
    public function getBuildingRent4(): int
    {
        return $this->buildingRent4;
    }

    /**
     * @param int $buildingRent4
     */
    public function setBuildingRent4(int $buildingRent4): void
    {
        $this->buildingRent4 = $buildingRent4;
    }

    /**
     * @return int
     */
    public function getBuildingRent5(): int
    {
        return $this->buildingRent5;
    }

    /**
     * @param int $buildingRent5
     */
    public function setBuildingRent5(int $buildingRent5): void
    {
        $this->buildingRent5 = $buildingRent5;
    }

    /**
     * @return int
     */
    public function getMortgage(): int
    {
        return $this->mortgage;
    }

    /**
     * @param int $mortgage
     */
    public function setMortgage(int $mortgage): void
    {
        $this->mortgage = $mortgage;
    }

    /**
     * @return int
     */
    public function getPlayFieldId(): int
    {
        return $this->playFieldId;
    }

    /**
     * @param int $playFieldId
     */
    public function setPlayFieldId(int $playFieldId): void
    {
        $this->playFieldId = $playFieldId;
    }
}