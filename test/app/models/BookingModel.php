<?php
class BookingModel {
    private $db;

    public function __construct() {
        require_once 'app/config/database.php';
        $this->db = new Database();
    }

    public function getAllBookings() {
        $this->db->query("SELECT b.*, c.first_name, c.last_name, m.title as movie_title, 
                                 t.name as theater_name, s.screen_number,
                                 st.show_date, st.show_time,
                                 (SELECT COUNT(*) FROM tickets WHERE booking_id = b.booking_id) as ticket_count
                         FROM bookings b
                         JOIN customers c ON b.customer_id = c.customer_id
                         JOIN showtimes st ON b.showtime_id = st.showtime_id
                         JOIN movies m ON st.movie_id = m.movie_id
                         JOIN screens s ON st.screen_id = s.screen_id
                         JOIN theaters t ON s.theater_id = t.theater_id
                         ORDER BY b.booking_date DESC");
        return $this->db->resultSet();
    }

    public function getBookingById($id) {
        $this->db->query("SELECT b.*, c.first_name, c.last_name, m.title as movie_title, 
                                 t.name as theater_name, s.screen_number,
                                 st.show_date, st.show_time
                         FROM bookings b
                         JOIN customers c ON b.customer_id = c.customer_id
                         JOIN showtimes st ON b.showtime_id = st.showtime_id
                         JOIN movies m ON st.movie_id = m.movie_id
                         JOIN screens s ON st.screen_id = s.screen_id
                         JOIN theaters t ON s.theater_id = t.theater_id
                         WHERE b.booking_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getBookingsByUserId($userId) {
        $this->db->query("SELECT b.*, m.title as movie_title, 
                                 t.name as theater_name, s.screen_number,
                                 st.show_date, st.show_time,
                                 (SELECT COUNT(*) FROM tickets WHERE booking_id = b.booking_id) as ticket_count
                         FROM bookings b
                         JOIN showtimes st ON b.showtime_id = st.showtime_id
                         JOIN movies m ON st.movie_id = m.movie_id
                         JOIN screens s ON st.screen_id = s.screen_id
                         JOIN theaters t ON s.theater_id = t.theater_id
                         WHERE b.customer_id = :customer_id AND b.payment_status != 'cancelled'
                         ORDER BY b.booking_date DESC");
        $this->db->bind(':customer_id', $userId);
        return $this->db->resultSet();
    }

    public function createBooking($data) {
        try {
            // Log data đầu vào để debug
            error_log("BookingModel::createBooking - Dữ liệu đầu vào: " . json_encode($data));
            
            // Kiểm tra table users
            if ($this->db->tableExists('users')) {
                // Kiểm tra user_id có tồn tại trong bảng users không
                $this->db->query("SELECT id FROM users WHERE id = :user_id");
                $this->db->bind(':user_id', $data['user_id']);
                $user = $this->db->single();
                
                if (!$user) {
                    error_log("BookingModel::createBooking - Không tìm thấy user_id trong bảng users: " . $data['user_id']);
                }
            } else {
                error_log("BookingModel::createBooking - Bảng 'users' không tồn tại.");
            }
            
            // Insert booking - Sử dụng ID người dùng trực tiếp
            $this->db->query("INSERT INTO bookings (customer_id, showtime_id, booking_date, total_amount, payment_status, payment_method) 
                             VALUES (:customer_id, :showtime_id, :booking_date, :total_amount, :payment_status, :payment_method)");
            
            $this->db->bind(':customer_id', $data['user_id']);
            $this->db->bind(':showtime_id', $data['showtime_id']);
            $this->db->bind(':booking_date', $data['booking_date']);
            $this->db->bind(':total_amount', $data['total_amount']);
            $this->db->bind(':payment_status', $data['payment_status']);
            $this->db->bind(':payment_method', $data['payment_method']);
            
            $result = $this->db->execute();
            
            if (!$result) {
                error_log("BookingModel::createBooking - Không thể tạo booking: " . $this->db->error());
                throw new Exception("Không thể tạo đơn đặt vé");
            }
            
            $lastId = $this->db->lastInsertId();
            error_log("BookingModel::createBooking - Đã tạo booking thành công với ID: " . $lastId);
            
            return $lastId;
        } catch (Exception $e) {
            error_log("BookingModel::createBooking - Lỗi: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateBooking($id, $data) {
        $query = "UPDATE bookings SET ";
        $params = [];
        
        foreach ($data as $key => $value) {
            $params[] = "$key = :$key";
        }
        
        $query .= implode(', ', $params);
        $query .= " WHERE booking_id = :booking_id";
        
        $this->db->query($query);
        
        // Bind parameters
        foreach ($data as $key => $value) {
            $this->db->bind(":$key", $value);
        }
        
        $this->db->bind(':booking_id', $id);
        
        return $this->db->execute();
    }

    public function getBookingStatistics() {
        $this->db->query("SELECT 
                            COUNT(*) as total_bookings,
                            SUM(CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END) as paid_bookings,
                            SUM(CASE WHEN payment_status = 'pending' THEN 1 ELSE 0 END) as pending_bookings,
                            SUM(CASE WHEN payment_status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_bookings,
                            SUM(total_amount) as total_revenue,
                            SUM(CASE WHEN payment_status = 'paid' THEN total_amount ELSE 0 END) as paid_revenue
                         FROM bookings");
        return $this->db->single();
    }

    public function getRecentBookings($limit = 10) {
        $this->db->query("SELECT b.*, c.first_name, c.last_name, m.title as movie_title, 
                                 t.name as theater_name, 
                                 st.show_date, st.show_time
                         FROM bookings b
                         JOIN customers c ON b.customer_id = c.customer_id
                         JOIN showtimes st ON b.showtime_id = st.showtime_id
                         JOIN movies m ON st.movie_id = m.movie_id
                         JOIN screens s ON st.screen_id = s.screen_id
                         JOIN theaters t ON s.theater_id = t.theater_id
                         ORDER BY b.booking_date DESC
                         LIMIT :limit");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function beginTransaction() {
        return $this->db->beginTransaction();
    }

    public function commit() {
        return $this->db->commit();
    }

    public function rollback() {
        return $this->db->rollback();
    }

    public function getBookingTickets($bookingId) {
        $this->db->query("SELECT t.*, s.seat_number 
                         FROM booking_tickets bt
                         JOIN tickets t ON bt.ticket_id = t.ticket_id
                         WHERE bt.booking_id = :booking_id");
        $this->db->bind(':booking_id', $bookingId);
        return $this->db->resultSet();
    }

    public function cancelBooking($id) {
        try {
            $this->db->beginTransaction();
            
            // Get all tickets for this booking
            $this->db->query("SELECT ticket_id FROM booking_tickets WHERE booking_id = :booking_id");
            $this->db->bind(':booking_id', $id);
            $tickets = $this->db->resultSet();
            
            // Update status of all tickets to available
            foreach ($tickets as $ticket) {
                $this->db->query("UPDATE tickets SET status = 'available' WHERE ticket_id = :ticket_id");
                $this->db->bind(':ticket_id', $ticket['ticket_id']);
                $this->db->execute();
            }
            
            // Delete from booking_tickets
            $this->db->query("DELETE FROM booking_tickets WHERE booking_id = :booking_id");
            $this->db->bind(':booking_id', $id);
            $this->db->execute();
            
            // Delete booking
            $this->db->query("DELETE FROM bookings WHERE booking_id = :booking_id");
            $this->db->bind(':booking_id', $id);
            $this->db->execute();
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Error in BookingModel::cancelBooking(): " . $e->getMessage());
            return false;
        }
    }

    // Thực thi truy vấn trực tiếp (không dùng prepared statement)
    private function executeRawQuery($query) {
        try {
            error_log("BookingModel::executeRawQuery - Thực thi truy vấn trực tiếp: " . $query);
            
            // Sử dụng mysqli thay vì PDO
            $conn = mysqli_connect('localhost', 'root', '', 'cinema_booking');
            if (!$conn) {
                error_log("BookingModel::executeRawQuery - Lỗi kết nối mysqli: " . mysqli_connect_error());
                return false;
            }
            
            // Thực thi truy vấn trực tiếp
            $result = mysqli_query($conn, $query);
            
            if ($result === false) {
                error_log("BookingModel::executeRawQuery - Lỗi mysqli: " . mysqli_error($conn));
                mysqli_close($conn);
                return false;
            }
            
            mysqli_close($conn);
            return true;
        } catch (Exception $e) {
            error_log("BookingModel::executeRawQuery - Exception: " . $e->getMessage());
            return false;
        }
    }
    
    public function createBookingTicket($bookingId, $ticketId) {
        try {
            // Kiểm tra dữ liệu đầu vào
            if (!$bookingId || !$ticketId) {
                error_log("createBookingTicket: bookingId hoặc ticketId không hợp lệ. bookingId: " . $bookingId . ", ticketId: " . $ticketId);
                return false;
            }
            
            // Chuyển đổi sang kiểu số nguyên để đảm bảo an toàn
            $bookingId = intval($bookingId);
            $ticketId = intval($ticketId);
            
            // Kiểm tra xem vé đã được đặt chưa
            $this->db->query("SELECT status FROM tickets WHERE ticket_id = :ticket_id");
            $this->db->bind(':ticket_id', $ticketId);
            $ticket = $this->db->single();
            
            if (!$ticket) {
                error_log("createBookingTicket: Không tìm thấy vé với ID " . $ticketId);
                return false;
            }
            
            // Kiểm tra xem liên kết đã tồn tại chưa
            $this->db->query("SELECT * FROM booking_tickets WHERE booking_id = :booking_id AND ticket_id = :ticket_id");
            $this->db->bind(':booking_id', $bookingId);
            $this->db->bind(':ticket_id', $ticketId);
            $existing = $this->db->single();
            
            if ($existing) {
                error_log("createBookingTicket: Liên kết đã tồn tại giữa booking ID " . $bookingId . " và ticket ID " . $ticketId);
                return true; // Liên kết đã tồn tại, coi như thành công
            }
            
            // Sử dụng prepared statement thay vì executeRawQuery
            $this->db->query("INSERT INTO booking_tickets (booking_id, ticket_id) VALUES (:booking_id, :ticket_id)");
            $this->db->bind(':booking_id', $bookingId);
            $this->db->bind(':ticket_id', $ticketId);
            $result = $this->db->execute();
            
            if ($result) {
                error_log("createBookingTicket: Đã tạo liên kết thành công giữa booking ID " . $bookingId . " và ticket ID " . $ticketId);
            } else {
                error_log("createBookingTicket: Lỗi khi tạo liên kết");
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("createBookingTicket: Exception: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return false;
        }
    }
} 