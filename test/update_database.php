<?php
// Kết nối đến cơ sở dữ liệu
$host = 'localhost';
$dbname = 'cinema';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Kiểm tra xem cột ticket_price đã tồn tại chưa
    $stmt = $conn->prepare("SHOW COLUMNS FROM showtimes LIKE 'ticket_price'");
    $stmt->execute();
    $columnExists = $stmt->rowCount() > 0;
    
    if (!$columnExists) {
        // Thêm cột ticket_price vào bảng showtimes
        $sql = "ALTER TABLE showtimes ADD COLUMN ticket_price DECIMAL(10,2) NOT NULL DEFAULT 50000 AFTER show_time";
        $conn->exec($sql);
        echo "Đã thêm cột ticket_price vào bảng showtimes thành công!";
    } else {
        echo "Cột ticket_price đã tồn tại trong bảng showtimes!";
    }
} catch(PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}

// Đóng kết nối
$conn = null;
?> 