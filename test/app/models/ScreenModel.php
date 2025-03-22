<?php
class ScreenModel {
    private $db;

    public function __construct() {
        require_once 'app/config/database.php';
        $this->db = new Database();
    }

    public function getAllScreens() {
        $this->db->query("SELECT s.*, t.name as theater_name 
                         FROM screens s
                         JOIN theaters t ON s.theater_id = t.theater_id
                         ORDER BY t.name, s.screen_number");
        return $this->db->resultSet();
    }

    public function getScreenById($id) {
        $this->db->query("SELECT s.*, t.name as theater_name 
                         FROM screens s
                         JOIN theaters t ON s.theater_id = t.theater_id
                         WHERE s.screen_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getScreensByTheaterId($theaterId) {
        $this->db->query("SELECT * FROM screens WHERE theater_id = :theater_id ORDER BY screen_number");
        $this->db->bind(':theater_id', $theaterId);
        return $this->db->resultSet();
    }

    public function addScreen($data) {
        $this->db->query("INSERT INTO screens (theater_id, screen_number, capacity, screen_type) 
                         VALUES (:theater_id, :screen_number, :capacity, :screen_type)");
        
        $this->db->bind(':theater_id', $data['theater_id']);
        $this->db->bind(':screen_number', $data['screen_number']);
        $this->db->bind(':capacity', $data['capacity']);
        $this->db->bind(':screen_type', $data['screen_type']);
        
        return $this->db->execute();
    }

    public function updateScreen($id, $data) {
        $this->db->query("UPDATE screens 
                         SET theater_id = :theater_id, 
                             screen_number = :screen_number, 
                             capacity = :capacity, 
                             screen_type = :screen_type 
                         WHERE screen_id = :id");
        
        $this->db->bind(':theater_id', $data['theater_id']);
        $this->db->bind(':screen_number', $data['screen_number']);
        $this->db->bind(':capacity', $data['capacity']);
        $this->db->bind(':screen_type', $data['screen_type']);
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }

    public function deleteScreen($id) {
        $this->db->query("DELETE FROM screens WHERE screen_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
} 