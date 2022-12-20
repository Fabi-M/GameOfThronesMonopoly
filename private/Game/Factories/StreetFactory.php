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

    /**
     * @param EntityManager $em
     * @param array $filter
     */
    public static function filter(EntityManager $em, array $filter): ?array
    {
        $entities = $em->getRepository(self::GAME_NAMESPACE)->findBy(
            array(
                'WHERE' => $filter
            )
        );

        $models = [];
        foreach($entities as $entity){
            $models []= new Street($entity);
        }
        return $models;
    }

    public static function getByFieldId($em, $fieldId){
        return self::filterOne($em, array(
                array('playfieldID', 'equal', $fieldId)
            )
        );
    }
}