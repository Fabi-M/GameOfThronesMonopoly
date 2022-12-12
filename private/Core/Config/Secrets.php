<?php

namespace GameOfThronesMonopoly\Config;
/**
 * Class Secrets
 * @author Selina Stöcklein
 * @date   12.12.2022
 */
class Secrets
{
    private static $dbSecrets = [
        'host' => 'localhost',
        'name' => 'monopoly',
        'user' => 'root',
        'pass' => ''
    ];

    /**
     * @author Selina Stöcklein
     * @date   12.12.2022
     **/
    public static function getSecretDB()
    {
        return self::$dbSecrets;
    }
}