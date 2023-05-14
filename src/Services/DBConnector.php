<?php

namespace App\Services;

use FaaPz\PDO\Database;

class DBConnector
{
    private static $instance = null;
    private $conn;

    private $dsn;
    private $user;
    private $pass;

    private function __construct()
    {
        $this->dsn  = $_ENV['DB_CONNECTION'].':host='.$_ENV['DB_HOST'].';dbname='.$_ENV['DB_DATABASE'].';charset=utf8';
        $this->user = $_ENV['DB_USERNAME'];
        $this->pass = $_ENV['DB_PASSWORD'];
        $this->conn = new Database($this->dsn, $this->user, $this->pass);

        $this->conn->query(
            'CREATE TABLE IF NOT EXISTS
                `persons` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `first_name` VARCHAR(255) NOT NULL,
                `last_name` VARCHAR(255) NOT NULL,
                `birth_date` DATE NOT NULL,
                `gender` TINYINT UNSIGNED NOT NULL,
                `city` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`id`)) ENGINE = InnoDB;'
        );
    }

    public static function getInstance()
    {
        if(!self::$instance)
        {
            self::$instance = new DBConnector();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        if(!self::$instance)
        {
            self::$instance = new DBConnector();
        }

        return $this->conn;
    }
}
