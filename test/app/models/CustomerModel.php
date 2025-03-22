<?php
require_once 'app/config/database.php';

class CustomerModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function register($data) {
        try {
            // Kiểm tra email đã tồn tại chưa
            $this->db->query("SELECT COUNT(*) FROM customers WHERE email = :email");
            $this->db->bind(':email', $data['email']);
            
            if ($this->db->single()['COUNT(*)'] > 0) {
                return ['error' => 'Email đã tồn tại trong hệ thống'];
            }

            $this->db->query("INSERT INTO customers (first_name, last_name, email, password, phone_number, address, role) 
                    VALUES (:first_name, :last_name, :email, :password, :phone_number, :address, :role)");
            
            $this->db->bind(':first_name', $data['first_name']);
            $this->db->bind(':last_name', $data['last_name']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':password', $data['password']);
            $this->db->bind(':phone_number', $data['phone_number']);
            $this->db->bind(':address', $data['address']);
            $this->db->bind(':role', 'user');

            if ($this->db->execute()) {
                return ['success' => true, 'message' => 'Đăng ký thành công'];
            } else {
                error_log("SQL Error in register");
                return ['error' => 'Không thể thêm dữ liệu vào database'];
            }
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            if ($e->getCode() == 23000) {
                return ['error' => 'Email đã tồn tại trong hệ thống'];
            }
            return ['error' => 'Đã có lỗi xảy ra: ' . $e->getMessage()];
        }
    }

    public function login($email) {
        try {
            $this->db->query("SELECT * FROM customers WHERE email = :email LIMIT 1");
            $this->db->bind(':email', $email);
            
            return $this->db->single();
        } catch (PDOException $e) {
            return ['error' => 'Đã có lỗi xảy ra khi đăng nhập'];
        }
    }

    public function getCustomerById($id) {
        try {
            $this->db->query("SELECT * FROM customers WHERE customer_id = :id LIMIT 1");
            $this->db->bind(':id', $id);
            
            return $this->db->single();
        } catch (PDOException $e) {
            return null;
        }
    }

    public function updateProfile($id, $data) {
        try {
            $this->db->query("UPDATE customers 
                    SET first_name = :first_name, 
                        last_name = :last_name, 
                        phone_number = :phone_number, 
                        address = :address 
                    WHERE customer_id = :id");
            
            $this->db->bind(':id', $id);
            $this->db->bind(':first_name', $data['first_name']);
            $this->db->bind(':last_name', $data['last_name']);
            $this->db->bind(':phone_number', $data['phone_number']);
            $this->db->bind(':address', $data['address']);

            return $this->db->execute();
        } catch (PDOException $e) {
            return ['error' => 'Đã có lỗi xảy ra khi cập nhật thông tin'];
        }
    }
} 