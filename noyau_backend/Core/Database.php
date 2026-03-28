<?php

namespace App\Core;

use PDO;
use MongoDB\Client;

class Database
{
    private static $mysql = null;
    private static $mongo = null;

    public static function getMySQL()
    {
        if (self::$mysql === null) {
            $host = getenv('DB_HOST') ?: 'localhost';
            $db   = getenv('DB_NAME') ?: 'sakura_ramen';
            $user = getenv('DB_USER') ?: 'sakura_user';
            $pass = getenv('DB_PASS') ?: 'sakura_password';
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$mysql = new PDO($dsn, $user, $pass, $options);
            } catch (\PDOException $e) {
                throw new \PDOException($e->getMessage(), (int)$e->getCode());
            }
        }
        return self::$mysql;
    }

    public static function getMongo()
    {
        if (self::$mongo === null) {
            $uri = getenv('MONGO_URI') ?: 'mongodb://localhost:27017';
            try {
                $client = new Client($uri);
                self::$mongo = $client->selectDatabase(getenv('MONGO_DB_NAME') ?: 'sakura_logs');
            } catch (\Exception $e) {
                error_log("MongoDB connection failed: " . $e->getMessage());
                return null;
            }
        }
        return self::$mongo;
    }
}
