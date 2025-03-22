<?php
class Database {
    private $host = "localhost";
    private $db_name = "cinema_booking";
    private $username = "root";
    private $password = "";
    private $conn;
    private $stmt;
    private $error;

    public function __construct() {
        try {
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8';
            $options = [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            echo "Lỗi kết nối cơ sở dữ liệu: " . $this->error;
        }
    }

    // Chuẩn bị câu truy vấn
    public function query($sql) {
        $this->stmt = $this->conn->prepare($sql);
    }

    // Bind giá trị vào câu truy vấn
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    // Thực thi câu truy vấn
    public function execute() {
        return $this->stmt->execute();
    }

    // Lấy nhiều bản ghi
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    // Lấy một bản ghi
    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }

    // Lấy số bản ghi bị ảnh hưởng
    public function rowCount() {
        return $this->stmt->rowCount();
    }

    // Lấy ID của bản ghi vừa thêm
    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }

    // Bắt đầu một transaction
    public function beginTransaction() {
        return $this->conn->beginTransaction();
    }

    // Commit transaction
    public function commit() {
        return $this->conn->commit();
    }

    // Rollback transaction
    public function rollback() {
        return $this->conn->rollBack();
    }
}