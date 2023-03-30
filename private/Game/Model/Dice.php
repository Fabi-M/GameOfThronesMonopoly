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
        //$result = [rand(1, 6), rand(1, 6)];
        $result = [1,1]; // steuerfeld;
        //$result = [1,1]; // pasch;
        //$result = [1,2]; // normal;
        $_SESSION["dice"] = array_sum($result);
        return $result;
    }
}