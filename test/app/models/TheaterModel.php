<?php
class TheaterModel {
    private $db;

    public function __construct() {
        require_once 'app/config/database.php';
        $this->db = new Database();
    }

    public function getAllTheaters() {
        $this->db->query("SELECT * FROM theaters ORDER BY name ASC");
        return $this->db->resultSet();
    }

    public function getTheaterById($id) {
        $this->db->query("SELECT * FROM theaters WHERE theater_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addTheater($data) {
        $this->db->query("INSERT INTO theaters (name, address, city, state, zip_code, phone_number) 
                          VALUES (:name, :address, :city, :state, :zip_code, :phone_number)");
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':city', $data['city']);
        $this->db->bind(':state', $data['state']);
        $this->db->bind(':zip_code', $data['zip_code']);
        $this->db->bind(':phone_number', $data['phone_number']);
        
        return $this->db->execute();
    }

    public function updateTheater($id, $data) {
        $this->db->query("UPDATE theaters 
                          SET name = :name, 
                              address = :address, 
                              city = :city, 
                              state = :state, 
                              zip_code = :zip_code, 
                              phone_number = :phone_number 
                          WHERE theater_id = :id");
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':city', $data['city']);
        $this->db->bind(':state', $data['state']);
        $this->db->bind(':zip_code', $data['zip_code']);
        $this->db->bind(':phone_number', $data['phone_number']);
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }

    public function deleteTheater($id) {
        $this->db->query("DELETE FROM theaters WHERE theater_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
} 