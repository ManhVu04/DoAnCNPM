<?php
// Script để thêm cột rows và seats_per_row vào bảng screens

// Kết nối database
require_once 'app/config/database.php';
$db = new Database();

try {
    // Kiểm tra xem cột rows đã tồn tại chưa
    $db->query("SHOW COLUMNS FROM screens LIKE 'rows'");
    $rowsExists = $db->single();
    
    // Kiểm tra xem cột seats_per_row đã tồn tại chưa
    $db->query("SHOW COLUMNS FROM screens LIKE 'seats_per_row'");
    $seatsPerRowExists = $db->single();
    
    // Bắt đầu transaction
    $db->beginTransaction();
    
    // Thêm cột rows nếu chưa tồn tại
    if (!$rowsExists) {
        $db->query("ALTER TABLE screens ADD COLUMN `rows` INT DEFAULT 8 AFTER `screen_type`");
        $db->execute();
        echo "Đã thêm cột 'rows' vào bảng screens<br>";
    } else {
        echo "Cột 'rows' đã tồn tại trong bảng screens<br>";
    }
    
    // Thêm cột seats_per_row nếu chưa tồn tại
    if (!$seatsPerRowExists) {
        $db->query("ALTER TABLE screens ADD COLUMN `seats_per_row` INT DEFAULT 10 AFTER `rows`");
        $db->execute();
        echo "Đã thêm cột 'seats_per_row' vào bảng screens<br>";
    } else {
        echo "Cột 'seats_per_row' đã tồn tại trong bảng screens<br>";
    }
    
    // Cập nhật giá trị mặc định cho các phòng chiếu hiện có
    $db->query("UPDATE screens SET `rows` = 8, `seats_per_row` = CEILING(capacity / 8) WHERE `rows` IS NULL OR `seats_per_row` IS NULL");
    $db->execute();
    echo "Đã cập nhật giá trị mặc định cho các phòng chiếu hiện có<br>";
    
    // Commit các thay đổi
    $db->commit();
    echo "Cập nhật cơ sở dữ liệu thành công!";
} catch (Exception $e) {
    // Rollback nếu có lỗi
    if ($db->inTransaction()) {
        $db->rollback();
    }
    echo "Lỗi: " . $e->getMessage();
}
?> 