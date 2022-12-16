<?php

namespace GameOfThronesMonopoly\Game\Factories;

use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Model\Game;
use GameOfThronesMonopoly\Game\Model\Street;

class StreetFactory
{
    const GAME_NAMESPACE = 'GameOfThronesMonopoly\Game:Entities:street';

    /**
     * @param EntityManager $em
     * @param array $filter
     */
    public static function filterOne(EntityManager $em, array $filter): Street | null
    {
        $entity = $em->getRepository(self::GAME_NAMESPACE)->findOneBy(
            array(
                'WHERE' => $filter
            )
        );

        if (empty($entity)) {
            return null;
        }
        return new Street($entity);
    }

}