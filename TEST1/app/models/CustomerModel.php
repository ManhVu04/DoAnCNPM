<?php
require_once 'app/config/database.php';

class CustomerModel {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function register($data) {
        try {
            // Kiểm tra email đã tồn tại chưa
            $checkEmail = "SELECT COUNT(*) FROM customers WHERE email = :email";
            $checkStmt = $this->conn->prepare($checkEmail);
            $checkStmt->execute([':email' => $data['email']]);
            
            if ($checkStmt->fetchColumn() > 0) {
                return ['error' => 'Email đã tồn tại trong hệ thống'];
            }

            $sql = "INSERT INTO customers (first_name, last_name, email, password, phone_number, address, role) 
                    VALUES (:first_name, :last_name, :email, :password, :phone_number, :address, :role)";
            
            $stmt = $this->conn->prepare($sql);
            $params = [
                ':first_name' => $data['first_name'],
                ':last_name' => $data['last_name'],
                ':email' => $data['email'],
                ':password' => $data['password'],
                ':phone_number' => $data['phone_number'],
                ':address' => $data['address'],
                ':role' => 'user'
            ];

            if ($stmt->execute($params)) {
                return ['success' => true, 'message' => 'Đăng ký thành công'];
            } else {
                $error = $stmt->errorInfo();
                error_log("SQL Error: " . print_r($error, true));
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
            $sql = "SELECT * FROM customers WHERE email = :email LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':email' => $email]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['error' => 'Đã có lỗi xảy ra khi đăng nhập'];
        }
    }

    public function getCustomerById($id) {
        try {
            $sql = "SELECT * FROM customers WHERE customer_id = :id LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function updateProfile($id, $data) {
        try {
            $sql = "UPDATE customers 
                    SET first_name = :first_name, 
                        last_name = :last_name, 
                        phone_number = :phone_number, 
                        address = :address 
                    WHERE customer_id = :id";
            
            $stmt = $this->conn->prepare($sql);
            $params = [
                ':id' => $id,
                ':first_name' => $data['first_name'],
                ':last_name' => $data['last_name'],
                ':phone_number' => $data['phone_number'],
                ':address' => $data['address']
            ];

            return $stmt->execute($params);
        } catch (PDOException $e) {
            return ['error' => 'Đã có lỗi xảy ra khi cập nhật thông tin'];
        }
    }
} 