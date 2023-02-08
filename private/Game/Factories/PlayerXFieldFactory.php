<?php

namespace GameOfThronesMonopoly\Game\Factories;

use Exception;
use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Entities\player_x_field;
use GameOfThronesMonopoly\Game\Model\Game;
use GameOfThronesMonopoly\Game\Model\PlayerXField;
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
    public static function filterOne(EntityManager $em, array $filter): PlayerXField|null
    {
        /** @var player_x_field $entity */
        $entity = $em->getRepository(self::GAME_NAMESPACE)->findOneBy(
            [
                'WHERE' => $filter
            ]
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
        return PlayerXFieldFactory::filterOne(
            $em,
            [
                ['gameId', 'equal', $gameId],
                ['fieldId', 'equal', $fieldId]
            ]
        );
    }

    /**
     * @param EntityManager $em
     * @param int           $playerId
     * @return array | PlayerXField[]
     * @throws ReflectionException
     * @author Selina Stöcklein
     */
    public static function getByPlayerId(EntityManager $em, int $playerId): array
    {
        return PlayerXFieldFactory::filter(
            $em,
            [
                ['playerId', 'equal', $playerId]
            ]
        );
    }

    /**
     * @param EntityManager $em
     * @param array         $filter
     * @return array | PlayerXField[]
     * @author Selina Stöcklein
     * @throws ReflectionException
     */
    private static function filter(EntityManager $em, array $filter): array
    {
        /** @var player_x_field[] $entities */
        $entities = $em->getRepository(self::GAME_NAMESPACE)->findBy(
            [
                'WHERE' => $filter
            ]
        );

        $ready = [];
        if (!empty($entities)) {
            foreach ($entities as $entity) {
                $ready[$entity->getFieldId()] = new PlayerXField($entity);
            }
        }
        return $ready;
    }
}