<?php

namespace GameOfThronesMonopoly\Game\Service;

use GameOfThronesMonopoly\Core\Datamapper\EntityManager;
use GameOfThronesMonopoly\Core\Exceptions\SQLException;
use GameOfThronesMonopoly\Game\Factories\StreetFactory;
use GameOfThronesMonopoly\Game\Model\Factory;
use GameOfThronesMonopoly\Game\Model\Game;
use GameOfThronesMonopoly\Game\Model\Player;
use GameOfThronesMonopoly\Game\Model\Street;
use GameOfThronesMonopoly\Game\Model\Trainstation;

/**
 * Class ScoreService
 * @author Selina Stöcklein
 * @date   16.02.2023
 */
class ScoreService
{
    public function __construct()
    {
    }

    /**
     * @throws \ReflectionException
     * @throws SQLException
     */
    public function calculateScore(array $players, Game $game, EntityManager $em): array
    {
        $streetService = new StreetService($game, $em);

        $scores = [];
        foreach ($players as $player) {
            $playerEntity=$player->getPlayerEntity();
            $score = $playerEntity->getMoney();
            if ($score <= 0) {
                $scores[$playerEntity->getIngameId()] = 0;
                continue;
            }
            /** @var Street[] $streets */
            $streets = StreetFactory::getAllByPlayerId($em, $playerEntity->getId());
            $streetScore = [];
            foreach ($streets as $street) {
                $streetEntity=$street->getStreetEntity();
                $color=$streetEntity->getColor();
                $streetScore['streetValues']=0;
                if (!isset($streetScore['bonus'][$color])
                    && $streetService->checkIfFullStreet($player, $street)) {
                    $streetScore['bonus'][$color]=true;
                    $streetScore['streetValues']+= 200;
                } else {
                    $streetScore['bonus'][$color]=false;
                }
                $streetScore['streetValues']+= $streetEntity->getStreetCosts();

                if ($street instanceof Trainstation || $street instanceof Factory){
                    // add StreetCosts again as bonus, 'cause they cant have buildings
                    $streetScore['streetValues']+= $streetEntity->getStreetCosts();
                }else{
                    $streetScore['streetValues']+= $streetEntity->getBuildingCosts() * $street->getXField()->getPlayerXStreetEntity()->getBuildings();
                }
            }
            $score+=$streetScore['streetValues'];
            $scores[$playerEntity->getIngameId()]['score']=$score;
            $scores[$playerEntity->getIngameId()]['inGamePlayerId']=$playerEntity->getIngameId();
        }
        usort($scores, [$this, 'sortByScore']);
        return $scores;
    }


    /** Sort scores
     * @param $a
     * @param $b
     * @return mixed
     */
    function sortByScore($a, $b) {
        return $b['score'] - $a['score'];
    }

}