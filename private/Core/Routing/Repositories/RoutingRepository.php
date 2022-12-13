<?php

namespace GameOfThronesMonopoly\Core\Routing\Repositories;

use GameOfThronesMonopoly\Core\DataBase\DataBaseConnection;
use Exception;
use PDO;
use GameOfThronesMonopoly\Core\Strings\ExceptionString;

/**
 * Class RoutingRepository
 * Interacts with route
 * Returns all rows
 */
class RoutingRepository
{
    /** get all routes from db
     * @return array
     * @throws Exception
     */
    public static function getAllRoutes(): array
    {
        return self::$allRoutes;
        $instance = DataBaseConnection::getInstance();
        $pdo = $instance->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM route");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($data)) {
            throw new Exception(ExceptionString::ROUTE_EXCEPTION);
        }
        return $data;
    }

    private static $allRoutes = [
        [
            'url' => '/test',
            'controller' => 'GameOfThronesMonopoly\Test\Controller\TestController',
            'action' => 'TestAction',
            'method' => 'get',
            'comment' => ''
        ],
        [
            'url' => '/testGame',
            'controller' => 'GameOfThronesMonopoly\Game\Controller\GameManagerController',
            'action' => 'endTurnAction',
            'method' => 'get',
            'comment' => ''
        ],
    ];
}