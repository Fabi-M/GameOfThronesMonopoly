<?php

namespace GameOfThronesMonopoly\Game\Factories;

use Exception;
use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Model\SpecialField;
use GameOfThronesMonopoly\Game\Model\Street;
use ReflectionException;

/**
 * Class PlayFieldFactory
 * @author Selina Stöcklein
 * @date   20.12.2022
 */
class PlayFieldFactory
{
    /**
     * @param EntityManager $em
     * @param int           $playFieldId
     * @return SpecialField|Street
     * @throws ReflectionException
     * @throws Exception
     * @author Selina Stöcklein
     */
    public static function getPlayField(EntityManager $em, int $playFieldId, ?int $gameId): SpecialField|Street
    {
        // search in street
        $card = StreetFactory::getByFieldId($em, $playFieldId, $gameId);
        // search in specialCards
        if (empty($card)) {
            $card = SpecialFieldFactory::getByFieldId($em, $playFieldId);
        }

        if (empty($card)) {
            throw new \Exception("no card found :(");
        }

        return $card;
    }
}