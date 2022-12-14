<?php

namespace GameOfThronesMonopoly\Game\Model;

/**
 * Class Dice
 * @author Selina Stöcklein
 * @date   13.12.2022
 */
class Dice
{
    /**
     * @return int[]
     */
    public function roll(): array
    {
        return [rand(1, 6), rand(1, 6)];
    }
}