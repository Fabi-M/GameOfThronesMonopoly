<?php

namespace GameOfThronesMonopoly\Core\Datamapper;


class Connection
{

    private $db;

    protected static $_instance = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * @return \PDO
     */
    public function getPDO()
    {
        return $this->db;
    }

    protected function __clone()
    {
    }
}