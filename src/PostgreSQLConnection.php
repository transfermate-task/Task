<?php

namespace src;

class PostgreSQLConnection
{
    /**
     * PostgreSQLConnection
     * @var $connection
     */
    private static $connection;

    /**
     * PostgreSQLConnection constructor.
     */
    protected function __construct()
    {

    }

    /**
     * Connect to the database and return an instance of \PDO object
     * @return \PDO
     * @throws \Exception
     */
    public function connect(): \PDO
    {
        $params = parse_ini_file('config/database.ini');
        if ($params === false) {
            throw new \Exception("Error reading parameters from database configuration file");
        }

        $connectionString = sprintf("pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
            $params['host'],
            $params['port'],
            $params['database'],
            $params['user'],
            $params['password']
        );

        $pdo = new \PDO($connectionString);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }

    /**
     * return an instance of the PostgreSQLConnection object
     * @return $connection
     */
    public static function getConnection()
    {
        if (null === static::$connection) {
            static::$connection = new static();
        }

        return static::$connection;
    }

    private function __clone()
    {

    }

    private function __wakeup()
    {

    }

}