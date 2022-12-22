<?php

namespace GameOfThronesMonopoly\Game\Model;

use GameOfThronesMonopoly\Game\Entities\specialField as specialFieldEntity;

/**
 * Class SpecialField
 * @author Selina Stöcklein
 * @date   20.12.2022
 */
class SpecialField
{
    protected specialFieldEntity $entity;

    /**
     * @param specialFieldEntity $entity
     */
    public function __construct(specialFieldEntity $entity)
    {
        $this->entity = $entity;
    }

    public function getEntity():specialFieldEntity {
        return $this->entity;
    }
}