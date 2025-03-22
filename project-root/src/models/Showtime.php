<?php
require_once __DIR__ . '/Model.php';

class Showtime extends Model {
    protected $table = 'showtimes'; // Tên bảng trong CSDL

    // Thêm lịch chiếu mới
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (movie_id, screen_id, show_date, show_time) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([
            $data['movie_id'],
            $data['screen_id'],
            $data['show_date'],
            $data['show_time']
        ]);
    }

    // Cập nhật lịch chiếu
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET 
                movie_id = ?, screen_id = ?, show_date = ?, show_time = ? 
                WHERE showtime_id = ?";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([
            $data['movie_id'],
            $data['screen_id'],
            $data['show_date'],
            $data['show_time'],
            $id
        ]);
    }

    // Lấy thông tin phim liên quan
    public function getMovie($showtimeId) {
        $sql = "SELECT m.* FROM movies m 
                JOIN showtimes s ON m.movie_id = s.movie_id 
                WHERE s.showtime_id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$showtimeId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin phòng chiếu liên quan
    public function getScreen($showtimeId) {
        $sql = "SELECT sc.* FROM screens sc 
                JOIN showtimes s ON sc.screen_id = s.screen_id 
                WHERE s.showtime_id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$showtimeId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}