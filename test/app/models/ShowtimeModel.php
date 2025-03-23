<?php
class ShowtimeModel {
    private $db;

    public function __construct() {
        require_once 'app/config/database.php';
        $this->db = new Database();
    }

    public function getAllShowtimes() {
        $this->db->query("SELECT st.*, 
                                 m.title as movie_title, 
                                 s.screen_number, 
                                 t.name as theater_name
                         FROM showtimes st
                         JOIN movies m ON st.movie_id = m.movie_id
                         JOIN screens s ON st.screen_id = s.screen_id
                         JOIN theaters t ON s.theater_id = t.theater_id
                         ORDER BY st.show_date, st.show_time");
        return $this->db->resultSet();
    }

    public function getShowtimeById($id) {
        $this->db->query("SELECT st.*, 
                                 m.title as movie_title, 
                                 s.screen_number, 
                                 t.name as theater_name
                         FROM showtimes st
                         JOIN movies m ON st.movie_id = m.movie_id
                         JOIN screens s ON st.screen_id = s.screen_id
                         JOIN theaters t ON s.theater_id = t.theater_id
                         WHERE st.showtime_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getShowtimesByMovieId($movieId) {
        $this->db->query("SELECT st.*, 
                                 m.title as movie_title, 
                                 s.screen_number, 
                                 t.name as theater_name
                         FROM showtimes st
                         JOIN movies m ON st.movie_id = m.movie_id
                         JOIN screens s ON st.screen_id = s.screen_id
                         JOIN theaters t ON s.theater_id = t.theater_id
                         WHERE st.movie_id = :movie_id AND st.show_date >= CURDATE()
                         ORDER BY st.show_date, st.show_time");
        $this->db->bind(':movie_id', $movieId);
        return $this->db->resultSet();
    }

    public function addShowtime($data) {
        $this->db->query("INSERT INTO showtimes (movie_id, screen_id, show_date, show_time, ticket_price) 
                         VALUES (:movie_id, :screen_id, :show_date, :show_time, :ticket_price)");
        
        $this->db->bind(':movie_id', $data['movie_id']);
        $this->db->bind(':screen_id', $data['screen_id']);
        $this->db->bind(':show_date', $data['show_date']);
        $this->db->bind(':show_time', $data['show_time']);
        $this->db->bind(':ticket_price', $data['ticket_price']);
        
        return $this->db->execute();
    }

    public function updateShowtime($id, $data) {
        $this->db->query("UPDATE showtimes 
                         SET movie_id = :movie_id, 
                             screen_id = :screen_id, 
                             show_date = :show_date, 
                             show_time = :show_time,
                             ticket_price = :ticket_price 
                         WHERE showtime_id = :id");
        
        $this->db->bind(':movie_id', $data['movie_id']);
        $this->db->bind(':screen_id', $data['screen_id']);
        $this->db->bind(':show_date', $data['show_date']);
        $this->db->bind(':show_time', $data['show_time']);
        $this->db->bind(':ticket_price', $data['ticket_price']);
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }

    public function deleteShowtime($id) {
        $this->db->query("DELETE FROM showtimes WHERE showtime_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    
    public function deleteShowtimeWithTickets($id) {
        try {
            // Xóa lịch chiếu với id cụ thể
            $this->db->query("DELETE FROM showtimes WHERE showtime_id = :id");
            $this->db->bind(':id', $id);
            
            $result = $this->db->execute();
            if (!$result) {
                error_log("Failed to delete showtime: " . $id);
                return false;
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Error in ShowtimeModel::deleteShowtimeWithTickets(): " . $e->getMessage());
            return false;
        }
    }
    
    public function getLastInsertId() {
        return $this->db->lastInsertId();
    }
    
    // Các phương thức quản lý giao dịch
    public function beginTransaction() {
        return $this->db->beginTransaction();
    }
    
    public function commit() {
        return $this->db->commit();
    }
    
    public function rollback() {
        return $this->db->rollback();
    }
} 