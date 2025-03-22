<?php
class Database {
    protected static $connection;

    public function __construct() {
        if (!self::$connection) {
            $host = 'localhost';
            $dbname = 'cinema_booking';
            $username = 'root';
            $password = '';

            try {
                self::$connection = new PDO(
                    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                    $username,
                    $password
                );
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Lỗi kết nối database: " . $e->getMessage());
            }
        }
    }

    public function getConnection() {
        return self::$connection;
    }
}