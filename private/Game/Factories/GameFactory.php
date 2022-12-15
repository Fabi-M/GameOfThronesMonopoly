<?php

namespace GameOfThronesMonopoly\Game\Factories;

use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Model\Game;

/**
 * Class UserFactory
 * Builds models of the user and returns them
 */
class GameFactory
{
    const GAME_NAMESPACE = 'GameOfThronesMonopoly\Game:Entities:Game';

    /**
     * @param EntityManager $em
     * @param array $filter
     */
    public static function filterOne(EntityManager $em, array $filter): Game | null
    {
        $entity = $em->getRepository(self::GAME_NAMESPACE)->findOneBy(
            array(
                'WHERE' => $filter
            )
        );

        if (empty($entity)) {
            return null;
        }
        echo "<pre>";
        var_dump($entity);
        return new Game($entity);
    }

}