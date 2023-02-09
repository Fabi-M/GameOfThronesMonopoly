<?php

namespace GameOfThronesMonopoly\Game\Factories;

use Exception;
use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Entities\street as streetEntity;
use GameOfThronesMonopoly\Game\Model\Factory;
use GameOfThronesMonopoly\Game\Model\Street;
use GameOfThronesMonopoly\Game\Model\Trainstation;
use ReflectionException;

class StreetFactory
{
    const STREET_NAMESPACE = 'GameOfThronesMonopoly\Game:Entities:street';

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
            $xField = PlayerXFieldFactory::getByFieldId($em, $gameId, $entity->getPlayFieldId());
        }

        if($entity->getColor() == "trainstation"){
            return new Trainstation($entity, $xField);
        }else if($entity->getColor() == "factory"){
            return new Factory($entity, $xField);
        }else{
            return new Street($entity, $xField);
        }
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
        $entities = $em->getRepository(self::STREET_NAMESPACE)->findBy(
            [
                'WHERE' => $filter
            ]
        );

        $models = [];
        foreach ($entities as $entity) {
            $xField = null;
            if (!empty($gameId)) {
                $xField = PlayerXFieldFactory::getByFieldId($em, $gameId, $entity->getPlayFieldId());
            }
            if($entity->getColor() == "trainstation"){
                $models [] = new Trainstation($entity, $xField);
            }else if($entity->getColor() == "factory"){
                $models [] = new Factory($entity, $xField);
            }else{
                $models [] = new Street($entity, $xField);
            }
        }
        return $models;
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
}