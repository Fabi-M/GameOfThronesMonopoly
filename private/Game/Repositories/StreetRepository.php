<?php

namespace GameOfThronesMonopoly\Game\Repositories;

use GameOfThronesMonopoly\Core\DataBase\DataBaseConnection;
use GameOfThronesMonopoly\Core\Repositories\BaseRepository;
use PDO;

class StreetRepository extends BaseRepository
{
    /**
     * Check if the player owns all streets of the given color
     * @author Fabian Müller , Selina Stöcklein
     * @throws \GameOfThronesMonopoly\Core\Exceptions\SQLException
     */
    public static function checkIfAllStreetsOwned($color, $playerId){
        $pdo = self::getPDO();

        $query = "
SELECT IF(
    (SELECT COUNT(*) FROM street s WHERE s.color=:color)
        = 
    (SELECT COUNT(*) FROM player_x_field pf LEFT JOIN street s ON pf.fieldId = s.playfieldId WHERE s.color=:color AND pf.playerId=:playerId)
    , 'true', 'false') AS bool;";

        $stmt = $pdo->prepare($query);
        $stmt->execute(array(
            ':color' => $color,
            ':playerId' => $playerId
        ));
        self::checkForError(__CLASS__, $stmt);
        return $stmt->fetch(PDO::FETCH_ASSOC)["bool"];
    }
}