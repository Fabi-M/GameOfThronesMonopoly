<?php

namespace GameOfThronesMonopoly\Game\Model;

use GameOfThronesMonopoly\Game\Factories\PlayerXStreetFactory;

class Trainstation extends Street
{
    private $ids = [5, 15, 25, 35];

    public function getRent($em = null, $playerId = null): mixed
    {
        $amount = PlayerXStreetFactory::filter(
            $em,
            [
                'WHERE' => [
                    ['playerId', 'equal', $playerId],
                ],
                "IN" => [
                    "fieldId" => $this->ids
                ]
            ]

        );
        return 25 * pow(2, count($amount) - 1);
    }
}