<?php

namespace GameOfThronesMonopoly\Game\Factories;

use Exception;
use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Model\Game;
use GameOfThronesMonopoly\Game\Model\Player;
use ReflectionException;

class PlayerFactory
{
    const GAME_NAMESPACE = 'GameOfThronesMonopoly\Game:Entities:player';

    /**
     * @param EntityManager $em
     * @param array         $filter
     * @return Player|null
     * @throws ReflectionException
     */
    public static function filterOne(EntityManager $em, array $filter): Player|null
    {
        $entity = $em->getRepository(self::GAME_NAMESPACE)->findOneBy(
            [
                'WHERE' => $filter
            ]
        );

        if (empty($entity)) {
            return null;
        }
        return new Player($entity);
    }

    /**
     * @param EntityManager $em
     * @param Game|null     $game
     * @return Player
     * @author Selina Stöcklein
     * @throws Exception
     */
    public static function getActivePlayer(EntityManager $em, ?Game $game): Player
    {
        $player = self::filterOne(
            $em,
            [
                //TODO 19.12.2022 Selina: lieber mti gameid abrufen
                ['sessionId', 'equal', $game->getGameEntity()->getSessionId()],
                ['ingameId', 'equal', $game->getGameEntity()->getActivePlayerId()]
            ]
        );
        if (empty($player)) {
            throw new Exception('no player found');
        }
        return $player;
    }
}