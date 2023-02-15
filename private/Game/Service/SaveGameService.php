<?php

namespace GameOfThronesMonopoly\Game\Service;

use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Game\Factories\StreetFactory;
use GameOfThronesMonopoly\Game\Model\Game;
use GameOfThronesMonopoly\Game\Model\Player;
use GameOfThronesMonopoly\Game\Model\Street;

/**
 * Class SaveGameService
 * @author Selina Stöcklein
 * @date   09.02.2023
 */
class SaveGameService
{
    /**
     * @param Game           $game
     * @param array|Player[] $players
     * @param EntityManager  $em
     * @return array
     * @throws \ReflectionException
     */
    public function getPlayfieldInitConfig(Game $game, array $players, EntityManager $em)
    {
        $saveGame = [];
        $activePlayer = $players[$game->getGameEntity()->getActivePlayerId()];
        $saveGame['activePlayerId'] = $activePlayer->getPlayerEntity()->getIngameId();
        $saveGame['rolledDice'] = $game->getGameEntity()->getRolledDice();
        $saveGame['activePlayerMoney'] = $activePlayer->getPlayerEntity()->getMoney();

        foreach ($players as $player) {
            /** @var int $inGamePlayerId 1,2,3,4 */
            $inGamePlayerId = $player->getPlayerEntity()->getIngameId();
            /** @var int $playerId DB primary key */
            $playerId = $player->getPlayerEntity()->getId();
            $saveGame['playfield'][$player->getPlayerEntity()->getPosition()]['player'][] = $inGamePlayerId;
            $streets = StreetFactory::getAllByPlayerId($em, $playerId);
            foreach ($streets as $street) {
                $saveGame['playfield'][$street->getStreetEntity()->getPlayFieldId()]['owner'] = $inGamePlayerId;
                $saveGame['playfield'][$street->getStreetEntity()->getPlayFieldId()]['buildings'] = $street->getXField()
                    ->getPlayerXStreetEntity()
                    ->getBuildings();
            }
        }
        //TODO 09.02.2023 Selina: use to show the bought streets of the current player
        /** @var Street[] $streets */
        $activePlayerStreets = StreetFactory::getAllByPlayerId(
            $em, $activePlayer->getPlayerEntity()->getId()
        );
        $saveGame['activePlayerStreets'] = implode('<br>---------------<br>',
            array_map(function (Street $street) {
                return $street->getSimpleInfo();
            }, $activePlayerStreets));
        return $saveGame;
    }
}