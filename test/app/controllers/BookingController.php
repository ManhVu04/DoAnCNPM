<?php
require_once 'app/models/BookingModel.php';
require_once 'app/models/ShowtimeModel.php';
require_once 'app/models/TicketModel.php';
require_once 'app/models/MovieModel.php';
require_once 'app/models/ScreenModel.php';
require_once 'app/helpers/SessionHelper.php';

class BookingController {
    private $bookingModel;
    private $showtimeModel;
    private $ticketModel;
    private $movieModel;
    private $screenModel;
    private $db;

    public function __construct() {
        $this->bookingModel = new BookingModel();
        $this->showtimeModel = new ShowtimeModel();
        $this->ticketModel = new TicketModel();
        $this->movieModel = new MovieModel();
        $this->screenModel = new ScreenModel();
        
        require_once 'app/config/database.php';
        $this->db = new Database();
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
            $_SESSION['error_message'] = "Suất chiếu không tồn tại";
            header('Location: /test/Movies/index');
            exit();
        }
        
        $tickets = $this->ticketModel->getTicketsByShowtimeId($showtimeId);
        
        if (empty($tickets)) {
            $isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
            include 'app/views/booking/no_tickets.php';
            exit();
        }
        
        $screen = $this->screenModel->getScreenById($showtime['screen_id']);
        
        include 'app/views/booking/seats.php';
    }

    public function generateTickets($showtimeId) {
        SessionHelper::requireAdmin();
        
        $showtime = $this->showtimeModel->getShowtimeById($showtimeId);
        if (!$showtime) {
            $_SESSION['error_message'] = "Suất chiếu không tồn tại";
            header('Location: /test/Movies/index');
            exit();
        }
        
        $screenId = $showtime['screen_id'];
        if ($this->ticketModel->generateTicketsForShowtime($showtimeId, $screenId)) {
            $_SESSION['success_message'] = "Đã tạo vé cho suất chiếu thành công!";
        } else {
            $_SESSION['error_message'] = "Không thể tạo vé cho suất chiếu. Vui lòng kiểm tra thông tin phòng chiếu.";
        }
        
        header('Location: /test/Booking/seats/' . $showtimeId);
        exit();
    }

    public function confirm() {
        SessionHelper::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /test/Movie/list');
            exit();
        }
        
        $showtimeId = isset($_POST['showtime_id']) ? intval($_POST['showtime_id']) : 0;
        $ticketIds = isset($_POST['ticket_ids']) ? $_POST['ticket_ids'] : [];
        
        if (empty($ticketIds) || !$showtimeId) {
            $_SESSION['error_message'] = "Không có vé nào được chọn hoặc suất chiếu không hợp lệ";
            header('Location: /test/Booking/seats/' . $showtimeId);
            exit();
        }
        
        // Nếu ticket_ids được gửi dưới dạng chuỗi (từ form), chuyển đổi thành mảng
        if (!is_array($ticketIds)) {
            $ticketIds = explode(',', $ticketIds);
        }
        
        // Chuẩn hóa ticketIds thành số nguyên và loại bỏ các giá trị không hợp lệ
        $validTicketIds = [];
        foreach ($ticketIds as $ticketId) {
            $ticketId = intval($ticketId);
            if ($ticketId > 0) {
                $validTicketIds[] = $ticketId;
            }
        }
        
        if (empty($validTicketIds)) {
            $_SESSION['error_message'] = "Không có vé hợp lệ nào được chọn";
            header('Location: /test/Booking/seats/' . $showtimeId);
            exit();
        }
        
        error_log("BookingController::confirm - Xác nhận đặt vé cho showtime ID: " . $showtimeId . ", tickets: " . implode(',', $validTicketIds));
        
        $showtime = $this->showtimeModel->getShowtimeById($showtimeId);
        $selectedTickets = [];
        $totalAmount = 0;
        
        foreach ($validTicketIds as $ticketId) {
            $ticket = $this->ticketModel->getTicketById($ticketId);
            if ($ticket && $ticket['status'] === 'available') {
                $selectedTickets[] = $ticket;
                $totalAmount += floatval($ticket['price']);
            }
        }
        
        // Tạo chuỗi ticket_ids để truyền qua form
        $ticketIdsString = implode(',', array_column($selectedTickets, 'ticket_id'));
        
        include 'app/views/booking/confirm.php';
    }

    public function process() {
        SessionHelper::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /test/Movie/list');
            exit();
        }
        
        $showtimeId = isset($_POST['showtime_id']) ? intval($_POST['showtime_id']) : 0;
        $ticketIds = isset($_POST['ticket_ids']) ? explode(',', $_POST['ticket_ids']) : [];
        $totalAmount = isset($_POST['total_amount']) ? floatval($_POST['total_amount']) : 0;
        $paymentMethod = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : 'at_counter';
        
        // Kiểm tra dữ liệu đầu vào chi tiết hơn
        if (empty($ticketIds) || !$showtimeId || $totalAmount <= 0) {
            $errorMsg = "Dữ liệu đặt vé không hợp lệ: ";
            if (!$showtimeId) $errorMsg .= "Suất chiếu không hợp lệ. ";
            if (empty($ticketIds)) $errorMsg .= "Không có vé nào được chọn. ";
            if ($totalAmount <= 0) $errorMsg .= "Tổng tiền không hợp lệ. ";
            
            error_log("BookingController::process - " . $errorMsg);
            $_SESSION['error_message'] = $errorMsg;
            header('Location: /test/Booking/seats/' . $showtimeId);
            exit();
        }
        
        // Xác thực các ticket_id là số nguyên hợp lệ
        $validTicketIds = [];
        foreach ($ticketIds as $ticketId) {
            $ticketId = intval($ticketId);
            if ($ticketId > 0) {
                $validTicketIds[] = $ticketId;
            } else {
                error_log("BookingController::process - Bỏ qua ticket_id không hợp lệ: " . $ticketId);
            }
        }
        
        if (empty($validTicketIds)) {
            $_SESSION['error_message'] = "Không có vé hợp lệ nào được chọn.";
            header('Location: /test/Booking/seats/' . $showtimeId);
            exit();
        }
        
        // Tạo đơn đặt vé mới
        $customerId = SessionHelper::getUserId();
        $bookingData = [
            'user_id' => intval($customerId),
            'showtime_id' => $showtimeId,
            'total_amount' => $totalAmount,
            'payment_method' => $paymentMethod,
            'payment_status' => ($paymentMethod === 'credit_card') ? 'paid' : 'pending',
            'booking_date' => date('Y-m-d H:i:s')
        ];
        
        try {
            $this->bookingModel->beginTransaction();
            
            // Ghi log dữ liệu booking để debug
            error_log("BookingController::process - Tạo booking với dữ liệu: " . json_encode($bookingData));
            
            // Tạo booking
            $bookingId = $this->bookingModel->createBooking($bookingData);
            
            if (!$bookingId) {
                throw new Exception("Không thể tạo đơn đặt vé. Không có ID trả về.");
            }
            
            error_log("BookingController::process - Đã tạo booking với ID: " . $bookingId);
            
            // Cập nhật trạng thái các vé
            foreach ($validTicketIds as $ticketId) {
                error_log("BookingController::process - Cập nhật vé ID: " . $ticketId);
                // Cập nhật trạng thái vé thành 'booked'
                $ticketData = [
                    'status' => 'booked'
                ];
                
                if (!$this->ticketModel->updateTicket($ticketId, $ticketData)) {
                    throw new Exception("Không thể cập nhật trạng thái vé ID: " . $ticketId);
                }
                
                // Thêm vào bảng booking_tickets để liên kết booking với ticket
                error_log("BookingController::process - Tạo liên kết booking_ticket cho booking ID: " . $bookingId . " và ticket ID: " . $ticketId);
                if (!$this->bookingModel->createBookingTicket($bookingId, $ticketId)) {
                    throw new Exception("Không thể liên kết vé với đơn đặt vé. Booking ID: " . $bookingId . ", Ticket ID: " . $ticketId);
                }
            }
            
            $this->bookingModel->commit();
            error_log("BookingController::process - Đặt vé thành công với booking ID: " . $bookingId);
            
            // Chuyển hướng đến trang thành công
            $_SESSION['success_message'] = "Đặt vé thành công! Mã đơn hàng của bạn là: " . $bookingId;
            header('Location: /test/Booking/success/' . $bookingId);
            exit();
        } catch (Exception $e) {
            $this->bookingModel->rollback();
            
            $errorMessage = $e->getMessage();
            error_log("BookingController::process - Lỗi trong quá trình đặt vé: " . $errorMessage);
            error_log("BookingController::process - Stack trace: " . $e->getTraceAsString());
            
            $_SESSION['error_message'] = "Có lỗi xảy ra khi đặt vé: " . $errorMessage;
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