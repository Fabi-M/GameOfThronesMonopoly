<?php

namespace GameOfThronesMonopoly\Game\Entities;

class street
{
    protected int $id;
    protected string $name;
    protected int $price;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return street
     */
    public function setId(int $id): street
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return street
     */
    public function setName(string $name): street
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     * @return street
     */
    public function setPrice(int $price): street
    {
        $this->price = $price;
        return $this;
    }


}