<?php

namespace GameOfThronesMonopoly\Game\Factories;

use Exception;
use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Core\Exceptions\SQLException;
use GameOfThronesMonopoly\Game\Entities\player_x_street;
use GameOfThronesMonopoly\Game\Model\PlayerXStreet;
use GameOfThronesMonopoly\Game\Repositories\StreetRepository;
use ReflectionException;

class PlayerXStreetFactory
{
    const GAME_NAMESPACE = 'GameOfThronesMonopoly\Game:Entities:Player_x_street';

    /**
     * @param EntityManager $em
     * @param array         $filter
     * @return PlayerXStreet|null
     * @throws ReflectionException
     * @throws Exception
     * @author Fabian Müller
     */
    public static function filterOne(EntityManager $em, array $filter): PlayerXStreet|null
    {
        /** @var player_x_street $entity */
        $entity = $em->getRepository(self::GAME_NAMESPACE)->findOneBy(
            [
                'WHERE' => $filter
            ]
        );

        if (empty($entity)) {
            return null;
        }
        return new PlayerXStreet($entity);
    }

    /**
     * @param EntityManager $em
     * @param               $gameId
     * @param               $fieldId
     * @return PlayerXStreet|null
     * @throws ReflectionException
     * @throws SQLException
     * @author Fabian Müller
     */
    public static function getByFieldId(EntityManager $em, $gameId, $fieldId): ?PlayerXStreet
    {

        $streetId = StreetRepository::getStreetIdFromPlayField($fieldId);
        return PlayerXStreetFactory::filterOne(
            $em,
            [
                ['gameId', 'equal', $gameId],
                ['streetId', 'equal', $streetId]
            ]
        );
    }

    /**
     * @param EntityManager $em
     * @param int           $playerId
     * @return array | PlayerXStreet[]
     * @throws ReflectionException
     * @author Selina Stöcklein
     */
    public static function getByPlayerId(EntityManager $em, int $playerId): array
    {
        return PlayerXStreetFactory::filter(
            $em,
            [
                "WHERE" => [
                    ['playerId', 'equal', $playerId]
                ]
            ]
        );
    }

    /**
     * @param EntityManager $em
     * @param array         $filter
     * @return array | PlayerXStreet[]
     * @throws ReflectionException
     * @author Selina Stöcklein
     */
    public static function filter(EntityManager $em, array $filter): array
    {
        /** @var player_x_street[] $entities */
        $entities = $em->getRepository(self::GAME_NAMESPACE)->findBy(
            $filter
        );

        $ready = [];
        if (!empty($entities)) {
            foreach ($entities as $entity) {
                $ready[$entity->getStreetId()] = new PlayerXStreet($entity);
            }
        }
        return $ready;
    }
}