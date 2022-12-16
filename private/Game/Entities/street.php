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


}