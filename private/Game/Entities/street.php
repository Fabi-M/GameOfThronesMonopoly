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
    protected $id;
    protected $name;
    protected $streetCosts;
    protected $color;
    protected $buildingCosts;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return street
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * @return street
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
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
     * @return street
     */
    public function setStreetCosts($streetCosts)
    {
        $this->streetCosts = $streetCosts;
        return $this;
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
     * @return street
     */
    public function setColor($color)
    {
        $this->color = $color;
        return $this;
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
     * @return street
     */
    public function setBuildingCosts($buildingCosts)
    {
        $this->buildingCosts = $buildingCosts;
        return $this;
    }

}