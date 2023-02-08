<?php

namespace GameOfThronesMonopoly\Game\Factories;

use Exception;
use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Model\Game;
use GameOfThronesMonopoly\Game\Model\PlayerXField;
use GameOfThronesMonopoly\Game\Model\Street;
use ReflectionException;

class PlayerXFieldFactory
{
    const GAME_NAMESPACE = 'GameOfThronesMonopoly\Game:Entities:player_x_field';

    /**
     * @param EntityManager $em
     * @param array         $filter
     * @return PlayerXField|null
     * @throws ReflectionException
     * @throws Exception
     * @author Fabian Müller
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

    /**
     * @param EntityManager $em
     * @param               $gameId
     * @param               $fieldId
     * @return PlayerXField|null
     * @throws ReflectionException
     * @author Fabian Müller
     */
    public static function getByFieldId(EntityManager $em, $gameId, $fieldId): ?PlayerXField
    {
        return PlayerXFieldFactory::filterOne($em, array(
                array('gameId', 'equal', $gameId),
                array('fieldId', 'equal', $fieldId)
            )
        );
    }

    /**
     * @param EntityManager $em
     * @param array         $filter
     * @return array|null
     * @throws ReflectionException
     * @throws Exception
     * @author Fabian Müller
     */
    public static function filter(EntityManager $em, array $where, array $in = null): ?array
    {
        $entities = $em->getRepository(self::GAME_NAMESPACE)->findBy(
            [
                'WHERE' => $where,
                "IN" => $in
            ]
        );
        return $entities;
    }
}