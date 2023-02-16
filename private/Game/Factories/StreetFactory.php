<?php

namespace GameOfThronesMonopoly\Game\Factories;

use Exception;
use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Entities\player_x_street;
use GameOfThronesMonopoly\Game\Entities\street as streetEntity;
use GameOfThronesMonopoly\Game\Model\PlayerXStreet;
use GameOfThronesMonopoly\Game\Model\Factory;
use GameOfThronesMonopoly\Game\Model\Street;
use GameOfThronesMonopoly\Game\Model\Trainstation;
use ReflectionException;

class StreetFactory
{
    const STREET_NAMESPACE = 'GameOfThronesMonopoly\Game:Entities:Street';

    /**
     * @param EntityManager $em
     * @param array         $filter
     * @param int|null      $gameId
     * @return Street|null
     * @throws ReflectionException
     * @throws Exception
     */
    public static function filterOne(EntityManager $em, array $filter, ?int $gameId = null): Street|null
    {
        /** @var streetEntity $entity */
        $entity = $em->getRepository(self::STREET_NAMESPACE)->findOneBy(
            [
                'WHERE' => $filter
            ]
        );

        if (empty($entity)) {
            return null;
        }
        $xField = null;
        if (!empty($gameId)) {
            $xField = PlayerXStreetFactory::getByFieldId($em, $gameId, $entity->getPlayFieldId());
        }

        return self::getModel($entity, $xField);
    }

    /**
     * @param EntityManager $em
     * @param array         $filter
     * @return array|null
     * @throws ReflectionException
     * @throws Exception
     * @author Fabian Müller
     */
    public static function filter(EntityManager $em, array $filter): ?array
    {
        /** @var streetEntity $entities */
        $entities = $em->getRepository(self::STREET_NAMESPACE)->findBy(
            [
                'WHERE' => $filter
            ]
        );

        $models = [];
        foreach ($entities as $entity) {
            $xField = null;
            if (!empty($gameId)) {
                $xField = PlayerXStreetFactory::getByFieldId($em, $gameId, $entity->getPlayFieldId());
            }
            $models[] = self::getModel($entity, $xField);
        }
        return $models;
    }

    /**
     * @throws ReflectionException
     */
    public static function getAllByPlayerId(EntityManager $em, int $playerId)
    {
        $readyModels = [];
        $playerXfields = PlayerXStreetFactory::getByPlayerId($em, $playerId);
        if (!empty($playerXfields)) {
            $streetIds = array_map(
                fn(PlayerXStreet $playerXField) => $playerXField->getPlayerXStreetEntity()->getStreetId(),
                $playerXfields
            );

            /** @var streetEntity[] $entities */
            $entities = $em->getRepository(self::STREET_NAMESPACE)->findBy(
                [
                    'IN' => ['id' => $streetIds]
                ]
            );

            if (!empty($entities)) {
                foreach ($entities as $entity) {
                    $readyModels[] = self::getModel($entity, $playerXfields[$entity->getId()]);
                }
            }
        }

        return $readyModels;
    }

    /**
     * @param EntityManager $em
     * @param int           $fieldId
     * @param ?int          $gameId
     * @return Street|null
     * @throws ReflectionException
     * @author Fabian Müller
     */
    public static function getByFieldId(EntityManager $em, int $fieldId, ?int $gameId = null): ?Street
    {
        return self::filterOne(
            $em,
            [
                ['playfieldID', 'equal', $fieldId]
            ],
            $gameId
        );
    }

    /**
     * Get a Model based on the given Entity
     * @param streetEntity       $entity
     * @param PlayerXStreet|null $xField
     * @return Factory|Street|Trainstation
     * @author Selina Stöcklein
     */
    private static function getModel(streetEntity $entity, ?PlayerXStreet $xField): Trainstation|Street|Factory
    {
        if ($entity->getColor() == "trainstation") {
            $model = new Trainstation($entity, $xField);
        } elseif ($entity->getColor() == "factory") {
            $model = new Factory($entity, $xField);
        } else {
            $model = new Street($entity, $xField);
        }
        return $model;
    }
}