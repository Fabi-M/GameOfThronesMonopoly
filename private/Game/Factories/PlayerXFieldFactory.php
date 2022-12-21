<?php

namespace GameOfThronesMonopoly\Game\Factories;

use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Model\Game;
use GameOfThronesMonopoly\Game\Model\PlayerXField;

class PlayerXFieldFactory
{
    const GAME_NAMESPACE = 'GameOfThronesMonopoly\Game:Entities:player_x_field';

    /**
     * @param EntityManager $em
     * @param array $filter
     */
    public static function filterOne(EntityManager $em, array $filter): PlayerXField | null
    {
        $entity = $em->getRepository(self::GAME_NAMESPACE)->findOneBy(
            array(
                'WHERE' => $filter
            )
        );

        if (empty($entity)) {
            return null;
        }
        return new PlayerXField($entity);
    }

    public static function getByFieldId(EntityManager $em, $gameId, $fieldId): ?PlayerXField
    {
        return PlayerXFieldFactory::filterOne($em, array(
                array('gameId', 'equal', $gameId),
                array('fieldId', 'equal', $fieldId)
            )
        );
    }
}