<?php
class Database {
    private $host = "localhost";
    private $db_name = "cinema_booking";
    private $username = "root";
    private $password = "";
    private $conn;
    private $stmt;
    private $error;
    private $inTransaction = false;

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
        // Kiểm tra xem câu truy vấn có chứa biến đã được bind sẵn không
        $has_placeholders = (strpos($sql, ':') !== false || strpos($sql, '?') !== false);
        
        try {
            if ($has_placeholders) {
                // Truy vấn có placeholders, sử dụng prepared statement
                $this->stmt = $this->conn->prepare($sql);
            } else {
                // Truy vấn trực tiếp, không có placeholders
                error_log("Database::query - Thực thi direct query: " . $sql);
                $this->stmt = $this->conn->query($sql);
                if (!$this->stmt) {
                    $error = $this->conn->errorInfo();
                    error_log("Database::query - Lỗi thực thi direct query: " . ($error[2] ?? 'Unknown error'));
                }
            }
        } catch (PDOException $e) {
            error_log("Database::query - Exception: " . $e->getMessage());
            throw $e;
        }
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
        try {
            if (!$this->stmt) {
                error_log("Database::execute - Không có câu query nào được chuẩn bị");
                return false;
            }
            
            // Kiểm tra nếu là PDOStatement (prepared statement) hay PDO query result
            if ($this->stmt instanceof PDOStatement) {
                // Với prepared statement, cần gọi execute()
                $queryString = $this->stmt->queryString;
                error_log("Database::execute - Thực thi prepared statement: " . $queryString);
                
                $result = $this->stmt->execute();
                
                if (!$result) {
                    $error = $this->stmt->errorInfo();
                    error_log("Database::execute - Lỗi thực thi prepared statement: " . ($error[2] ?? 'Unknown error'));
                }
                
                return $result;
            } else {
                // Với direct query, kết quả đã có sẵn
                error_log("Database::execute - Direct query đã được thực thi trước đó");
                return true;
            }
        } catch (PDOException $e) {
            error_log("Database::execute - Exception: " . $e->getMessage());
            throw $e;
        }
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
        try {
            // Nếu đã có giao dịch đang chạy, không tạo mới
            if ($this->conn->inTransaction()) {
                $this->inTransaction = true;
                return true;
            }
            
            // Bắt đầu giao dịch mới
            $result = $this->conn->beginTransaction();
            if ($result) {
                $this->inTransaction = true;
            }
            return $result;
        } catch (PDOException $e) {
            error_log("Error in Database::beginTransaction(): " . $e->getMessage());
            return false;
        }
    }

    // Commit transaction
    public function commit() {
        try {
            // Chỉ commit khi thực sự có giao dịch đang chạy
            if ($this->conn->inTransaction()) {
                $result = $this->conn->commit();
                if ($result) {
                    $this->inTransaction = false;
                }
                return $result;
            }
            return true; // Trả về true nếu không có giao dịch nào để commit
        } catch (PDOException $e) {
            error_log("Error in Database::commit(): " . $e->getMessage());
            return false;
        }
    }

    // Rollback transaction
    public function rollback() {
        try {
            // Chỉ rollback khi thực sự có giao dịch đang chạy
            if ($this->conn->inTransaction()) {
                $result = $this->conn->rollBack();
                if ($result) {
                    $this->inTransaction = false;
                }
                return $result;
            }
            return true; // Trả về true nếu không có giao dịch nào để rollback
        } catch (PDOException $e) {
            error_log("Error in Database::rollback(): " . $e->getMessage());
            return false;
        }
    }
    
    // Kiểm tra xem có đang trong giao dịch hay không
    public function inTransaction() {
        // Sử dụng PDO->inTransaction() trực tiếp cho độ chính xác cao hơn
        return $this->conn->inTransaction();
    }

    // Lấy thông báo lỗi từ PDO
    public function error() {
        $error = $this->stmt->errorInfo();
        return $error[2] ?? 'Unknown error';
    }

    // Kiểm tra xem bảng có tồn tại không
    public function tableExists($tableName) {
        // Tránh SQL injection bằng cách escape thủ công
        $tableName = str_replace("'", "\'", $tableName);
        $query = "SHOW TABLES LIKE '$tableName'";
        $this->query($query);
        $result = $this->resultSet();
        return !empty($result);
    }
}