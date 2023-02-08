<?php

namespace GameOfThronesMonopoly\Game\Model;

use GameOfThronesMonopoly\Game\Factories\PlayerXFieldFactory;

class Trainstation extends Street
{
    private $ids = [5,15,25,35];

    public function getRent($em = null, $playerId = null): mixed
    {
        $amount = PlayerXFieldFactory::filter($em,
            [
                ['playerId', 'equal', $playerId],
            ],
            [
                "fieldId"  => $this->ids
            ]
        );
        return 25*pow(2, count($amount)-1);
    }
}