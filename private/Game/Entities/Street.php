<?php

namespace GameOfThronesMonopoly\Game\Entities;

class Street
{
    private int $id;
    private string $name;
    private int $price;

    /**
     * @param int $id
     * @param string $name
     * @param int $price
     */
    public function __construct(int $id, string $name, int $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Street
     */
    public function setId(int $id): Street
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
     * @return Street
     */
    public function setName(string $name): Street
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
     * @return Street
     */
    public function setPrice(int $price): Street
    {
        $this->price = $price;
        return $this;
    }


}