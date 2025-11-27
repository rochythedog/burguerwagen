<?php
class Database
{
    private static ?mysqli $connection = null;

    public static function getConnection(): mysqli
    {
        if (self::$connection === null) {
            self::$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            if (self::$connection->connect_error) {
                die('Database connection error: ' . self::$connection->connect_error);
            }

            self::$connection->set_charset('utf8mb4');
        }
        return self::$connection;
    }
}
