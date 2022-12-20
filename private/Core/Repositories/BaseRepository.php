<?php

namespace GameOfThronesMonopoly\Core\Repositories;

use GameOfThronesMonopoly\Core\DataBase\DataBaseConnection;
use GameOfThronesMonopoly\Core\Datamapper\Repository\MainRepositoryClass;
use PDO;
use PDOStatement;
use GameOfThronesMonopoly\Core\Exceptions\SQLException;

/**
 * Class BaseRepository
 * Parent of all custom repositories
 * Checks for sql errors and returns the last added primaryKey
 */
class BaseRepository extends MainRepositoryClass
{

    /**
     * Return the PrimaryKey of the last added record
     * @param PDO $pdo
     * @return int
     */
    protected static function getLastInsertedPK(PDO $pdo): int
    {
        $stmtGetId = $pdo->prepare("SELECT LAST_INSERT_ID();");
        $stmtGetId->execute();
        $id = $stmtGetId->fetch();

        return $id["LAST_INSERT_ID()"];
    }

    /**
     * Throws an exception if there was an error while executing the last statement
     * @param string $__CLASS__
     * @param PDOStatement $stmt
     * @return void
     * @throws SQLException
     */
    protected static function checkForError(string $__CLASS__, PDOStatement $stmt): void
    {
        $error = $stmt->errorInfo();
        if ($error[1] != 0) {
            throw new SQLException("SQL ERROR in Repo " . $__CLASS__ . ": " . $error[1] . ": " . $error[2]);
        }
    }

    protected static function getPDO(){
        return DataBaseConnection::getInstance()->getConnection();
    }
}