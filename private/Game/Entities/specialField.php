<?php

namespace GameOfThronesMonopoly\Game\Entities;

use GameOfThronesMonopoly\Core\Datamapper\BaseEntity;

/**
 * Class specialField
 * @author     Selina Stöcklein
 * @date       20.12.2022
 * @primaryKey id
 * @Repository
 */
class specialField extends BaseEntity
{
    protected int $id;
    protected int $playfieldId;
    protected ?int $price;
    protected string $action;
    protected string $name;
    protected string $comment;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getPlayfieldId(): int
    {
        return $this->playfieldId;
    }

    /**
     * @param int $playfieldId
     */
    public function setPlayfieldId(int $playfieldId): void
    {
        $this->playfieldId = $playfieldId;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param ?int $price
     */
    public function setPrice(?int $price): void
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction(string $action): void
    {
        $this->action = $action;
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
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }
}