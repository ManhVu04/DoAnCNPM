<?php
class TicketModel {
    private $db;

    public function __construct() {
        require_once 'app/config/database.php';
        $this->db = new Database();
    }

    public function getAllTickets() {
        $this->db->query("SELECT t.*, st.show_date, st.show_time, m.title as movie_title
                         FROM tickets t
                         JOIN showtimes st ON t.showtime_id = st.showtime_id
                         JOIN movies m ON st.movie_id = m.movie_id
                         ORDER BY st.show_date, st.show_time, t.seat_number");
        return $this->db->resultSet();
    }

    public function getTicketById($id) {
        $this->db->query("SELECT t.*, st.show_date, st.show_time, m.title as movie_title
                         FROM tickets t
                         JOIN showtimes st ON t.showtime_id = st.showtime_id
                         JOIN movies m ON st.movie_id = m.movie_id
                         WHERE t.ticket_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getTicketsByShowtimeId($showtimeId) {
        $this->db->query("SELECT * FROM tickets WHERE showtime_id = :showtime_id ORDER BY seat_number");
        $this->db->bind(':showtime_id', $showtimeId);
        return $this->db->resultSet();
    }

    public function getTicketsByBookingId($bookingId) {
        $this->db->query("SELECT t.* 
                         FROM tickets t
                         JOIN booking_tickets bt ON t.ticket_id = bt.ticket_id
                         WHERE bt.booking_id = :booking_id 
                         ORDER BY t.seat_number");
        $this->db->bind(':booking_id', $bookingId);
        return $this->db->resultSet();
    }

    public function getAvailableTicketsByShowtimeId($showtimeId) {
        $this->db->query("SELECT * FROM tickets 
                         WHERE showtime_id = :showtime_id AND status = 'available'
                         ORDER BY seat_number");
        $this->db->bind(':showtime_id', $showtimeId);
        return $this->db->resultSet();
    }

    public function generateTicketsForShowtime($showtimeId, $screenId) {
        try {
            // Kiểm tra xem đã có vé cho suất chiếu này chưa
            $this->db->query("SELECT COUNT(*) as ticket_count FROM tickets WHERE showtime_id = :showtime_id");
            $this->db->bind(':showtime_id', $showtimeId);
            $result = $this->db->single();
            
            if ($result && $result['ticket_count'] > 0) {
                // Đã có vé, xóa các vé hiện tại nếu chưa được đặt
                $this->db->query("SELECT COUNT(*) as booked_count FROM tickets 
                                 WHERE showtime_id = :showtime_id AND status = 'booked'");
                $this->db->bind(':showtime_id', $showtimeId);
                $bookedResult = $this->db->single();
                
                if ($bookedResult && $bookedResult['booked_count'] > 0) {
                    // Có vé đã được đặt, không thể tạo lại vé
                    error_log("Cannot regenerate tickets, there are already booked tickets");
                    return false;
                }
            }
            
            // Get screen capacity and layout
            $this->db->query("SELECT * FROM screens WHERE screen_id = :screen_id");
            $this->db->bind(':screen_id', $screenId);
            $screen = $this->db->single();
            
            if (!$screen) {
                error_log("Screen not found: " . $screenId);
                return false;
            }
            
            // Nếu không có thông tin số hàng và số ghế mỗi hàng, sử dụng giá trị mặc định
            $rows = isset($screen['rows']) && $screen['rows'] > 0 ? $screen['rows'] : 8;
            $seatsPerRow = isset($screen['seats_per_row']) && $screen['seats_per_row'] > 0 ? $screen['seats_per_row'] : 12;
            
            // Get showtime details for price
            $this->db->query("SELECT ticket_price FROM showtimes WHERE showtime_id = :showtime_id");
            $this->db->bind(':showtime_id', $showtimeId);
            $showtime = $this->db->single();
            
            if (!$showtime) {
                error_log("Showtime not found: " . $showtimeId);
                return false;
            }
            
            // Lấy giá vé, mặc định 75000 nếu không có
            $price = isset($showtime['ticket_price']) && $showtime['ticket_price'] > 0 ? $showtime['ticket_price'] : 75000;
            
            $this->db->beginTransaction();
            
            // Delete any existing tickets for this showtime
            $this->db->query("DELETE FROM tickets WHERE showtime_id = :showtime_id AND (status = 'available' OR status IS NULL)");
            $this->db->bind(':showtime_id', $showtimeId);
            $this->db->execute();
            
            // Generate new tickets based on row and seats per row
            $rowLetters = range('A', 'Z');
            $ticketsCreated = 0;
            
            for ($rowIndex = 0; $rowIndex < $rows; $rowIndex++) {
                $rowLetter = $rowLetters[$rowIndex];
                
                for ($seatNum = 1; $seatNum <= $seatsPerRow; $seatNum++) {
                    $seatNumber = $rowLetter . sprintf('%02d', $seatNum);
                    
                    // Kiểm tra xem ghế này đã tồn tại và đã đặt chưa
                    $this->db->query("SELECT ticket_id FROM tickets 
                                     WHERE showtime_id = :showtime_id AND seat_number = :seat_number AND status = 'booked'");
                    $this->db->bind(':showtime_id', $showtimeId);
                    $this->db->bind(':seat_number', $seatNumber);
                    $existingBookedSeat = $this->db->single();
                    
                    if (!$existingBookedSeat) {
                        // Chỉ tạo vé mới nếu ghế này chưa được đặt
                        $this->db->query("INSERT INTO tickets (showtime_id, seat_number, price, status) 
                                         VALUES (:showtime_id, :seat_number, :price, 'available')");
                        $this->db->bind(':showtime_id', $showtimeId);
                        $this->db->bind(':seat_number', $seatNumber);
                        $this->db->bind(':price', $price);
                        $this->db->execute();
                        $ticketsCreated++;
                    }
                }
            }
            
            $this->db->commit();
            error_log("Tickets created: " . $ticketsCreated);
            return $ticketsCreated > 0; // Trả về true nếu đã tạo ít nhất 1 vé
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            error_log("Error in TicketModel::generateTicketsForShowtime(): " . $e->getMessage());
            return false;
        }
    }

    public function updateTicket($id, $data) {
        try {
            error_log("TicketModel::updateTicket - Cập nhật vé ID: " . $id . " với dữ liệu: " . json_encode($data));
            
            // Kiểm tra dữ liệu đầu vào
            if (empty($data) || !is_array($data)) {
                error_log("TicketModel::updateTicket - Dữ liệu không hợp lệ cho vé ID: " . $id);
                return false;
            }
            
            // Kiểm tra vé tồn tại không
            $this->db->query("SELECT * FROM tickets WHERE ticket_id = :id");
            $this->db->bind(':id', $id);
            $ticket = $this->db->single();
            
            if (!$ticket) {
                error_log("TicketModel::updateTicket - Không tìm thấy vé với ID: " . $id);
                return false;
            }
            
            // Tạo câu truy vấn UPDATE
            $query = "UPDATE tickets SET ";
            $params = [];
            
            // Tạo danh sách các cột được cập nhật
            $allowedColumns = ['status', 'price', 'seat_number'];
            foreach ($data as $key => $value) {
                if (in_array($key, $allowedColumns)) {
                    $params[] = "`$key` = :$key";
                } else {
                    error_log("TicketModel::updateTicket - Bỏ qua cột không được phép: " . $key);
                }
            }
            
            // Nếu không có cột nào được cập nhật, return true (không cần update)
            if (empty($params)) {
                error_log("TicketModel::updateTicket - Không có cột nào được cập nhật cho vé ID: " . $id);
                return true;
            }
            
            $query .= implode(', ', $params);
            $query .= " WHERE ticket_id = :ticket_id";
            
            error_log("TicketModel::updateTicket - SQL query: " . $query);
            
            $this->db->query($query);
            
            // Bind các giá trị
            foreach ($data as $key => $value) {
                if (in_array($key, $allowedColumns)) {
                    $this->db->bind(":$key", $value);
                    error_log("TicketModel::updateTicket - Binding $key = $value");
                }
            }
            
            $this->db->bind(':ticket_id', $id);
            
            // Thực thi truy vấn
            $result = $this->db->execute();
            
            if ($result) {
                error_log("TicketModel::updateTicket - Cập nhật thành công vé ID: " . $id);
            } else {
                error_log("TicketModel::updateTicket - Lỗi khi cập nhật vé ID: " . $id . ". Lỗi: " . $this->db->error());
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("TicketModel::updateTicket - Exception: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return false;
        }
    }

    public function deleteTicket($id) {
        $this->db->query("DELETE FROM tickets WHERE ticket_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getScreenSeatMap($screenId) {
        $this->db->query("SELECT rows, seats_per_row FROM screens WHERE screen_id = :screen_id");
        $this->db->bind(':screen_id', $screenId);
        return $this->db->single();
    }

    public function getTicketCountByStatus() {
        $this->db->query("SELECT status, COUNT(*) as count 
                         FROM tickets 
                         GROUP BY status");
        return $this->db->resultSet();
    }

    public function countTicketsByStatus($showtimeId, $status) {
        $this->db->query("SELECT COUNT(*) as count FROM tickets 
                         WHERE showtime_id = :showtime_id AND status = :status");
        $this->db->bind(':showtime_id', $showtimeId);
        $this->db->bind(':status', $status);
        $result = $this->db->single();
        return $result ? intval($result['count']) : 0;
    }

    public function deleteTicketsByShowtimeId($showtimeId) {
        try {
            // Kiểm tra xem có vé nào đã được đặt không
            $this->db->query("SELECT COUNT(*) as booked_count FROM tickets 
                             WHERE showtime_id = :showtime_id AND status = 'booked'");
            $this->db->bind(':showtime_id', $showtimeId);
            $bookedResult = $this->db->single();
            
            if ($bookedResult && $bookedResult['booked_count'] > 0) {
                // Kiểm tra xem có vé nào liên kết với booking không
                $this->db->query("SELECT COUNT(*) as booking_count FROM booking_tickets bt
                                 JOIN tickets t ON bt.ticket_id = t.ticket_id
                                 WHERE t.showtime_id = :showtime_id");
                $this->db->bind(':showtime_id', $showtimeId);
                $bookingResult = $this->db->single();
                
                if ($bookingResult && $bookingResult['booking_count'] > 0) {
                    // Có vé đã liên kết với booking, không thể xóa
                    error_log("Cannot delete tickets with existing bookings for showtime: " . $showtimeId);
                    return false;
                }
            }
            
            // Không có booking liên quan hoặc không có vé nào được đặt, tiến hành xóa
            $this->db->query("DELETE FROM tickets WHERE showtime_id = :showtime_id");
            $this->db->bind(':showtime_id', $showtimeId);
            return $this->db->execute();
        } catch (Exception $e) {
            error_log("Error in TicketModel::deleteTicketsByShowtimeId(): " . $e->getMessage());
            return false;
        }
    }
} 