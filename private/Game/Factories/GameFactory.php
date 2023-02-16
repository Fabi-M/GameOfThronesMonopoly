<?php

namespace GameOfThronesMonopoly\Game\Factories;

use Exception;
use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Entities\game as gameEntity;
use GameOfThronesMonopoly\Game\Model\Game;
use ReflectionException;

class GameFactory
{
    const GAME_NAMESPACE = 'GameOfThronesMonopoly\Game:Entities:Game';

    /**
     * @param EntityManager $em
     * @param array         $filter
     * @return Game|null
     * @throws ReflectionException
     * @throws Exception
     */
    public static function filterOne(EntityManager $em, array $filter): Game|null
    {

        /** @var gameEntity $entity */
        $entity = $em->getRepository(self::GAME_NAMESPACE)->findOneBy(
            [
                'WHERE' => $filter
            ]
        );

        if (empty($entity)) {
            return null;
        }
        return new Game($entity);
    }

    /**
     * @param EntityManager $em
     * @param bool|string   $sessionId
     * @return Game|null
     * @throws ReflectionException
     * @author Selina Stöcklein
     */
    public static function getActiveGame(EntityManager $em, bool|string $sessionId): ?Game
    {
        return GameFactory::filterOne(
            $em,
            [
                ['sessionId', 'equal', $sessionId],
                ['gameOver', 'equal', 0]
            ]
        );
    }
}