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
        $this->db->query("SELECT * FROM tickets WHERE booking_id = :booking_id ORDER BY seat_number");
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
            // Get screen capacity and layout
            $this->db->query("SELECT capacity, rows, seats_per_row FROM screens WHERE screen_id = :screen_id");
            $this->db->bind(':screen_id', $screenId);
            $screen = $this->db->single();
            
            if (!$screen) {
                return false;
            }
            
            // Get showtime details for price
            $this->db->query("SELECT ticket_price FROM showtimes WHERE showtime_id = :showtime_id");
            $this->db->bind(':showtime_id', $showtimeId);
            $showtime = $this->db->single();
            
            if (!$showtime) {
                return false;
            }
            
            $price = $showtime['ticket_price'];
            $rows = $screen['rows'];
            $seatsPerRow = $screen['seats_per_row'];
            
            $this->db->beginTransaction();
            
            // Delete any existing tickets for this showtime
            $this->db->query("DELETE FROM tickets WHERE showtime_id = :showtime_id");
            $this->db->bind(':showtime_id', $showtimeId);
            $this->db->execute();
            
            // Generate new tickets based on row and seats per row
            $rowLetters = range('A', 'Z');
            
            for ($rowIndex = 0; $rowIndex < $rows; $rowIndex++) {
                $rowLetter = $rowLetters[$rowIndex];
                
                for ($seatNum = 1; $seatNum <= $seatsPerRow; $seatNum++) {
                    $seatNumber = $rowLetter . sprintf('%02d', $seatNum);
                    
                    $this->db->query("INSERT INTO tickets (showtime_id, seat_number, price, status) 
                                     VALUES (:showtime_id, :seat_number, :price, 'available')");
                    $this->db->bind(':showtime_id', $showtimeId);
                    $this->db->bind(':seat_number', $seatNumber);
                    $this->db->bind(':price', $price);
                    $this->db->execute();
                }
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Error in TicketModel::generateTicketsForShowtime(): " . $e->getMessage());
            return false;
        }
    }

    public function updateTicket($id, $data) {
        $query = "UPDATE tickets SET ";
        $params = [];
        
        foreach ($data as $key => $value) {
            $params[] = "$key = :$key";
        }
        
        $query .= implode(', ', $params);
        $query .= " WHERE ticket_id = :ticket_id";
        
        $this->db->query($query);
        
        // Bind parameters
        foreach ($data as $key => $value) {
            $this->db->bind(":$key", $value);
        }
        
        $this->db->bind(':ticket_id', $id);
        
        return $this->db->execute();
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
} 