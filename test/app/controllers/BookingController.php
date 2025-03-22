<?php
require_once 'app/models/BookingModel.php';
require_once 'app/models/ShowtimeModel.php';
require_once 'app/models/TicketModel.php';
require_once 'app/models/MovieModel.php';
require_once 'app/helpers/SessionHelper.php';

class BookingController {
    private $bookingModel;
    private $showtimeModel;
    private $ticketModel;
    private $movieModel;

    public function __construct() {
        $this->bookingModel = new BookingModel();
        $this->showtimeModel = new ShowtimeModel();
        $this->ticketModel = new TicketModel();
        $this->movieModel = new MovieModel();
    }

    public function index() {
        SessionHelper::requireLogin();
        
        $customerId = SessionHelper::getUserId();
        $bookings = $this->bookingModel->getBookingsByUserId($customerId);
        include 'app/views/booking/list.php';
    }

    public function showtime($movieId) {
        SessionHelper::requireLogin();
        
        $movie = $this->movieModel->getMovieById($movieId);
        if (!$movie) {
            header('Location: /test/Movie/list');
            exit();
        }
        
        $showtimes = $this->showtimeModel->getShowtimesByMovieId($movieId);
        include 'app/views/booking/showtimes.php';
    }

    public function seats($showtimeId) {
        SessionHelper::requireLogin();
        
        $showtime = $this->showtimeModel->getShowtimeById($showtimeId);
        if (!$showtime) {
            header('Location: /test/Movie/list');
            exit();
        }
        
        $tickets = $this->ticketModel->getTicketsByShowtimeId($showtimeId);
        include 'app/views/booking/seats.php';
    }

    public function confirm() {
        SessionHelper::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /test/Movie/list');
            exit();
        }
        
        $showtimeId = $_POST['showtime_id'] ?? 0;
        $ticketIds = $_POST['ticket_ids'] ?? [];
        
        if (empty($ticketIds) || !$showtimeId) {
            header('Location: /test/Booking/seats/' . $showtimeId);
            exit();
        }
        
        $showtime = $this->showtimeModel->getShowtimeById($showtimeId);
        $selectedTickets = [];
        $totalAmount = 0;
        
        foreach ($ticketIds as $ticketId) {
            $ticket = $this->ticketModel->getTicketById($ticketId);
            if ($ticket && $ticket['status'] === 'available') {
                $selectedTickets[] = $ticket;
                $totalAmount += $ticket['price'];
            }
        }
        
        include 'app/views/booking/confirm.php';
    }

    public function process() {
        SessionHelper::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /test/Movie/list');
            exit();
        }
        
        $showtimeId = $_POST['showtime_id'] ?? 0;
        $ticketIds = isset($_POST['ticket_ids']) ? explode(',', $_POST['ticket_ids']) : [];
        $totalAmount = $_POST['total_amount'] ?? 0;
        $paymentMethod = $_POST['payment_method'] ?? 'at_counter';
        
        if (empty($ticketIds) || !$showtimeId) {
            header('Location: /test/Booking/seats/' . $showtimeId);
            exit();
        }
        
        // Tạo đơn đặt vé mới
        $customerId = SessionHelper::getUserId();
        $bookingData = [
            'user_id' => $customerId,
            'showtime_id' => $showtimeId,
            'total_amount' => $totalAmount,
            'payment_method' => $paymentMethod,
            'payment_status' => ($paymentMethod === 'credit_card') ? 'paid' : 'pending',
            'booking_date' => date('Y-m-d H:i:s')
        ];
        
        try {
            $this->bookingModel->beginTransaction();
            
            // Tạo booking
            $bookingId = $this->bookingModel->createBooking($bookingData);
            
            if (!$bookingId) {
                throw new Exception("Không thể tạo đơn đặt vé");
            }
            
            // Cập nhật trạng thái các vé
            foreach ($ticketIds as $ticketId) {
                $ticketData = [
                    'booking_id' => $bookingId,
                    'status' => 'booked'
                ];
                
                if (!$this->ticketModel->updateTicket($ticketId, $ticketData)) {
                    throw new Exception("Không thể cập nhật trạng thái vé");
                }
            }
            
            $this->bookingModel->commit();
            
            // Chuyển hướng đến trang thành công
            header('Location: /test/Booking/success/' . $bookingId);
            exit();
        } catch (Exception $e) {
            $this->bookingModel->rollback();
            
            $error = $e->getMessage();
            header('Location: /test/Booking/seats/' . $showtimeId);
            exit();
        }
    }

    public function success($bookingId) {
        SessionHelper::requireLogin();
        
        $booking = $this->bookingModel->getBookingById($bookingId);
        if (!$booking || ($booking['customer_id'] != SessionHelper::getUserId() && !SessionHelper::isAdmin())) {
            header('Location: /test/Movie/list');
            exit();
        }
        
        $tickets = $this->ticketModel->getTicketsByBookingId($bookingId);
        include 'app/views/booking/success.php';
    }

    public function detail($bookingId) {
        SessionHelper::requireLogin();
        
        $booking = $this->bookingModel->getBookingById($bookingId);
        if (!$booking || ($booking['customer_id'] != SessionHelper::getUserId() && !SessionHelper::isAdmin())) {
            header('Location: /test/Booking/index');
            exit();
        }
        
        $tickets = $this->ticketModel->getTicketsByBookingId($bookingId);
        include 'app/views/booking/detail.php';
    }

    public function cancel($bookingId) {
        SessionHelper::requireLogin();
        
        $booking = $this->bookingModel->getBookingById($bookingId);
        if (!$booking || ($booking['customer_id'] != SessionHelper::getUserId() && !SessionHelper::isAdmin())) {
            header('Location: /test/Booking/index');
            exit();
        }
        
        // Kiểm tra thời gian suất chiếu
        $showtime = $this->showtimeModel->getShowtimeById($booking['showtime_id']);
        $showtimeDateTime = strtotime($showtime['show_date'] . ' ' . $showtime['show_time']);
        
        // Nếu không phải admin và suất chiếu đã diễn ra, không cho phép hủy
        if (!SessionHelper::isAdmin() && $showtimeDateTime <= time()) {
            header('Location: /test/Booking/detail/' . $bookingId);
            exit();
        }
        
        try {
            $this->bookingModel->beginTransaction();
            
            // Lấy danh sách vé cần hủy
            $tickets = $this->ticketModel->getTicketsByBookingId($bookingId);
            
            // Cập nhật trạng thái booking
            $bookingData = [
                'payment_status' => 'cancelled'
            ];
            
            if (!$this->bookingModel->updateBooking($bookingId, $bookingData)) {
                throw new Exception("Không thể hủy đặt vé");
            }
            
            // Cập nhật trạng thái các vé
            foreach ($tickets as $ticket) {
                $ticketData = [
                    'booking_id' => null,
                    'status' => 'available'
                ];
                
                if (!$this->ticketModel->updateTicket($ticket['ticket_id'], $ticketData)) {
                    throw new Exception("Không thể hủy trạng thái vé");
                }
            }
            
            $this->bookingModel->commit();
            
            header('Location: /test/Booking/index');
            exit();
        } catch (Exception $e) {
            $this->bookingModel->rollback();
            
            $error = $e->getMessage();
            header('Location: /test/Booking/detail/' . $bookingId);
            exit();
        }
    }

    public function pay($bookingId) {
        SessionHelper::requireLogin();
        
        $booking = $this->bookingModel->getBookingById($bookingId);
        if (!$booking || ($booking['customer_id'] != SessionHelper::getUserId() && !SessionHelper::isAdmin())) {
            header('Location: /test/Booking/index');
            exit();
        }
        
        // Kiểm tra nếu đã thanh toán rồi
        if ($booking['payment_status'] === 'paid') {
            header('Location: /test/Booking/detail/' . $bookingId);
            exit();
        }
        
        // Giả lập việc thanh toán (trong thực tế sẽ gọi cổng thanh toán)
        $bookingData = [
            'payment_status' => 'paid',
            'payment_method' => 'credit_card' // Giả định thanh toán bằng thẻ
        ];
        
        if ($this->bookingModel->updateBooking($bookingId, $bookingData)) {
            header('Location: /test/Booking/success/' . $bookingId);
            exit();
        } else {
            $error = "Không thể cập nhật trạng thái thanh toán";
            header('Location: /test/Booking/detail/' . $bookingId);
            exit();
        }
    }
} 