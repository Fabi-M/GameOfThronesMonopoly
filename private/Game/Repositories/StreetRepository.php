<?php

namespace GameOfThronesMonopoly\Game\Repositories;

use GameOfThronesMonopoly\Core\DataBase\DataBaseConnection;
use GameOfThronesMonopoly\Core\Exceptions\SQLException;
use GameOfThronesMonopoly\Core\Repositories\BaseRepository;
use GameOfThronesMonopoly\Game\Model\Player;
use PDO;

class StreetRepository extends BaseRepository
{
    /**
     * Check if the player owns all streets of the given color
     * @author Fabian Müller , Selina Stöcklein
     * @throws SQLException
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

    public static function getAllStreetsOfPlayer(Player $player){
        $pdo = self::getPDO();
$query = '  SELECT * FROM street s
            LEFT JOIN player_x_field pxf
            ON s.playfieldId = pxf.fieldId
            WHERE pxf.playerId = :playerId';
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':playerId' => $player->getPlayerEntity()->getId()
                       ]);
        self::checkForError(__CLASS__, $stmt);


    }

}