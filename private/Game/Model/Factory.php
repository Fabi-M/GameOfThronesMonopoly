<?php

namespace GameOfThronesMonopoly\Game\Model;

use GameOfThronesMonopoly\Game\Factories\PlayerXStreetFactory;

class Factory extends Street
{
    private $ids = [12, 28];

    public function getRent($em = null, $playerId = null): mixed
    {
        if (!isset($_SESSION["dice"])) {
            throw new \Exception("Last dice result not set");
        }
        if (empty($em)) {
            $amount = [1];
        } else {
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
        }
        if (count($amount) == 2) {
            return 10 * $_SESSION["dice"];
        }
        return 4 * $_SESSION["dice"];
    }
}