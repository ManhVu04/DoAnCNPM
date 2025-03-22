<?php
class Database {
    private $host = "localhost";
    private $db_name = "cinema_booking";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->exec("set names utf8");
        } catch(PDOException $e) {
            echo "Lỗi kết nối: " . $e->getMessage();
        }

        return $this->conn;
    }

    public function prepare($sql) {
        return $this->getConnection()->prepare($sql);
    }
}