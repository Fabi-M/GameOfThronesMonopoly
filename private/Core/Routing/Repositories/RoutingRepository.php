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
        $instance = DataBaseConnection::getInstance();
        $pdo = $instance->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM Route");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($data)) {
            throw new Exception(ExceptionString::ROUTE_EXCEPTION);
        }
        return $data;
    }
}