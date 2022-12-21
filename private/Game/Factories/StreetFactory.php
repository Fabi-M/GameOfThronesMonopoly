<?php

namespace GameOfThronesMonopoly\Game\Factories;

use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Entities\street as streetEntity;
use GameOfThronesMonopoly\Game\Model\Game;
use GameOfThronesMonopoly\Game\Model\Street;
use ReflectionException;

class StreetFactory
{
    const STREET_NAMESPACE = 'GameOfThronesMonopoly\Game:Entities:street';

    /**
     * @param EntityManager $em
     * @param array         $filter
     * @return Street|null
     * @throws ReflectionException
     */
    public static function filterOne(EntityManager $em, array $filter): Street|null
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
        return new Street($entity);
    }

    /**
     * @param EntityManager $em
     * @param array         $filter
     * @return array|null
     * @throws ReflectionException
     * @author Fabian Müller
     */
    public static function filter(EntityManager $em, array $filter): ?array
    {
        $entities = $em->getRepository(self::STREET_NAMESPACE)->findBy(
            [
                'WHERE' => $filter
            ]
        );

        $models = [];
        foreach ($entities as $entity) {
            $models [] = new Street($entity);
        }
        return $models;
    }

    /**
     * @param $em
     * @param $fieldId
     * @return Street|null
     * @throws ReflectionException
     * @author Fabian Müller
     */
    public static function getByFieldId($em, $fieldId): ?Street
    {
        return self::filterOne(
            $em,
            [
                ['playfieldID', 'equal', $fieldId]
            ]
        );
    }
}