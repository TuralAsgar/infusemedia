<?php

namespace classes;

use Config;
use PDO;

class Database
{
    private static ?Database $instance = null;
    private PDO $connection;

    private function __construct()
    {
        $dbHost = Config::DB_HOST;
        $dbPort = Config::DB_PORT;
        $dbName = Config::DB_NAME;
        $dbUser = Config::DB_USER;
        $dbPass = Config::DB_PASS;
        // Private constructor to prevent direct instantiation
        $this->connection = new PDO("mysql:host=$dbHost:$dbPort;dbname=$dbName", $dbUser, $dbPass);
    }

    public static function getInstance(): Database
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
