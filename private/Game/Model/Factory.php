<?php

namespace GameOfThronesMonopoly\Game\Model;

use GameOfThronesMonopoly\Game\Factories\PlayerXFieldFactory;

class Factory extends Street
{
    private $ids = [12,28];

    public function getRent($em = null, $playerId = null): mixed
    {
        if(!isset($_SESSION["dice"])) throw new \Exception("Last dice result not set");

        $amount = PlayerXFieldFactory::filter($em,
            [
                ['playerId', 'equal', $playerId],
            ],
            [
                "fieldId"  => $this->ids
            ]
        );
        if(count($amount) == 2){
            return 10*$_SESSION["dice"];
        }
        return 4*$_SESSION["dice"];
    }
}