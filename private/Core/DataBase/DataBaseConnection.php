<?php

namespace GameOfThronesMonopoly\Core\DataBase;

use GameOfThronesMonopoly\Config\Secrets;
use PDO as PDO;

/**
 * Class DataBaseConnection
 * Singleton that holds the database connection to our azubi db
 */
class DataBaseConnection
{
// Hold the class instance.
    private static $instance = null;
    /** @var PDO */
    private $conn;


    /**
     * Constructor
     * DB connection is established
     */
    private function __construct()
    {
        $Data = Secrets::getSecretDB();
        $this->conn = new PDO(
            "mysql:host={$Data["host"]};dbname={$Data["name"]}", $Data["user"], $Data["pass"],
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));

        // enable errors
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }

    /**
     * @return DataBaseConnection
     */
    public static function getInstance(): DataBaseConnection
    {
        if (!self::$instance) {
            self::$instance = new DataBaseConnection();
        }

        return self::$instance;
    }

    /**
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->conn;
    }
}