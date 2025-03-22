<?php
require_once __DIR__ . '/../config/database.php';

abstract class Model {
    protected $table;
    protected $connection;

    public function __construct() {
        $db = new Database();
        $this->connection = $db->getConnection();
    }

    // Lấy tất cả bản ghi
    public function all() {
        $stmt = $this->connection->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tìm bản ghi theo ID
    public function find($id) {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Xóa bản ghi
    public function delete($id) {
        $stmt = $this->connection->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}