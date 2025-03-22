<?php
require_once __DIR__ . '/Model.php';

class Screen extends Model {
    protected $table = 'screens';

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (theater_id, screen_number, capacity, screen_type) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([
            $data['theater_id'],
            $data['screen_number'],
            $data['capacity'],
            $data['screen_type']
        ]);
    }

    // Lấy thông tin rạp chiếu liên quan
    public function getTheater($screenId) {
        $sql = "SELECT t.* FROM theaters t 
                JOIN screens s ON t.theater_id = s.theater_id 
                WHERE s.screen_id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$screenId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}