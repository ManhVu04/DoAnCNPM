<?php
require_once 'app/models/ShowtimeModel.php';
require_once 'app/models/MovieModel.php';
require_once 'app/models/ScreenModel.php';
require_once 'app/models/TicketModel.php';
require_once 'app/helpers/SessionHelper.php';

class ShowtimeController {
    private $showtimeModel;
    private $movieModel;
    private $screenModel;
    private $ticketModel;

    public function __construct() {
        $this->showtimeModel = new ShowtimeModel();
        $this->movieModel = new MovieModel();
        $this->screenModel = new ScreenModel();
        $this->ticketModel = new TicketModel();
    }

    // Hiển thị danh sách lịch chiếu
    public function index() {
        SessionHelper::requireAdmin();
        $showtimes = $this->showtimeModel->getAllShowtimes();
        include 'app/views/showtime/index.php';
    }

    // Hiển thị form thêm lịch chiếu
    public function create() {
        SessionHelper::requireAdmin();
        $movies = $this->movieModel->getAllMovies();
        $screens = $this->screenModel->getAllScreens();
        include 'app/views/showtime/create.php';
    }

    // Lưu lịch chiếu mới
    public function store() {
        SessionHelper::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra và validate dữ liệu đầu vào
            $movieId = isset($_POST['movie_id']) ? $_POST['movie_id'] : 0;
            $screenId = isset($_POST['screen_id']) ? $_POST['screen_id'] : 0;
            $showDate = isset($_POST['show_date']) ? $_POST['show_date'] : '';
            $showTime = isset($_POST['show_time']) ? $_POST['show_time'] : '';
            $ticketPrice = isset($_POST['ticket_price']) ? $_POST['ticket_price'] : 0;
            
            $error = null;
            
            // Kiểm tra dữ liệu hợp lệ
            if (!$movieId || !$screenId || empty($showDate) || empty($showTime) || !$ticketPrice) {
                $error = "Vui lòng điền đầy đủ thông tin lịch chiếu";
            } else {
                $data = [
                    'movie_id' => $movieId,
                    'screen_id' => $screenId,
                    'show_date' => $showDate,
                    'show_time' => $showTime,
                    'ticket_price' => $ticketPrice
                ];
                
                // Kiểm tra xem phim và phòng chiếu có tồn tại không
                $movie = $this->movieModel->getMovieById($movieId);
                $screen = $this->screenModel->getScreenById($screenId);
                
                if (!$movie) {
                    $error = "Phim không tồn tại trong hệ thống";
                } else if (!$screen) {
                    $error = "Phòng chiếu không tồn tại trong hệ thống";
                } else {
                    try {
                        // Tạo giao dịch để đảm bảo tính toàn vẹn dữ liệu
                        $this->showtimeModel->beginTransaction();
                        
                        // Thêm suất chiếu mới
                        if (!$this->showtimeModel->addShowtime($data)) {
                            error_log("Failed to add showtime");
                            throw new Exception("Không thể tạo lịch chiếu");
                        }
                        
                        // Lấy ID của showtime vừa tạo
                        $showtimeId = $this->showtimeModel->getLastInsertId();
                        
                        if (!$showtimeId) {
                            error_log("Failed to get last insert ID");
                            throw new Exception("Không thể xác định ID lịch chiếu vừa tạo");
                        }
                        
                        error_log("Creating tickets for showtime ID: " . $showtimeId . ", screen ID: " . $screenId);
                        
                        // Tạo vé cho suất chiếu
                        if (!$this->ticketModel->generateTicketsForShowtime($showtimeId, $screenId)) {
                            error_log("Failed to generate tickets for showtime: " . $showtimeId);
                            throw new Exception("Không thể tạo vé cho lịch chiếu");
                        }
                        
                        // Hoàn tất giao dịch
                        $this->showtimeModel->commit();
                        
                        // Thêm thông báo thành công
                        $_SESSION['success_message'] = "Thêm lịch chiếu thành công!";
                        
                        // Chuyển hướng đến trang danh sách lịch chiếu
                        header('Location: /test/Showtime/index');
                        exit();
                        
                    } catch (Exception $e) {
                        // Hoàn tác giao dịch nếu có lỗi
                        error_log("Exception in ShowtimeController::store(): " . $e->getMessage());
                        $this->showtimeModel->rollback();
                        $error = "Không thể tạo lịch chiếu: " . $e->getMessage();
                    }
                }
            }
            
            // Nếu có lỗi, hiển thị lại form với thông báo lỗi
            if ($error) {
                $movies = $this->movieModel->getAllMovies();
                $screens = $this->screenModel->getAllScreens();
                $_SESSION['error_message'] = $error;
                include 'app/views/showtime/create.php';
            }
        } else {
            // Nếu không phải POST request, chuyển hướng về trang create
            header('Location: /test/Showtime/create');
            exit();
        }
    }
    
    // Hiển thị form chỉnh sửa lịch chiếu
    public function edit($id) {
        SessionHelper::requireAdmin();
        
        $showtime = $this->showtimeModel->getShowtimeById($id);
        if (!$showtime) {
            header('Location: /test/Showtime/index');
            exit();
        }
        
        $movies = $this->movieModel->getAllMovies();
        $screens = $this->screenModel->getAllScreens();
        include 'app/views/showtime/edit.php';
    }
    
    // Cập nhật lịch chiếu
    public function update($id) {
        SessionHelper::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'movie_id' => $_POST['movie_id'],
                'screen_id' => $_POST['screen_id'],
                'show_date' => $_POST['show_date'],
                'show_time' => $_POST['show_time'],
                'ticket_price' => $_POST['ticket_price']
            ];
            
            if ($this->showtimeModel->updateShowtime($id, $data)) {
                $_SESSION['success_message'] = "Cập nhật lịch chiếu thành công!";
                header('Location: /test/Showtime/index');
                exit();
            } else {
                // Xử lý lỗi
                $showtime = $this->showtimeModel->getShowtimeById($id);
                $movies = $this->movieModel->getAllMovies();
                $screens = $this->screenModel->getAllScreens();
                $_SESSION['error_message'] = "Không thể cập nhật lịch chiếu. Vui lòng thử lại.";
                include 'app/views/showtime/edit.php';
            }
        }
    }
    
    // Xóa lịch chiếu
    public function delete($id) {
        SessionHelper::requireAdmin();
        
        try {
            // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
            $this->showtimeModel->beginTransaction();
            
            // Kiểm tra xem có vé nào đã được đặt cho suất chiếu này không
            $bookedTicketsCount = $this->ticketModel->countTicketsByStatus($id, 'booked');
            
            if ($bookedTicketsCount > 0) {
                // Nếu có vé đã được đặt, hiển thị thông báo lỗi
                $_SESSION['error_message'] = "Không thể xóa lịch chiếu vì có " . $bookedTicketsCount . " vé đã được đặt.";
                $this->showtimeModel->rollback();
                header('Location: /test/Showtime/index');
                exit();
            }
            
            // Xóa tất cả vé liên quan đến suất chiếu này trước
            if (!$this->ticketModel->deleteTicketsByShowtimeId($id)) {
                // Nếu xóa vé thất bại, rollback và hiển thị thông báo lỗi
                $_SESSION['error_message'] = "Không thể xóa vé liên quan đến lịch chiếu.";
                $this->showtimeModel->rollback();
                header('Location: /test/Showtime/index');
                exit();
            }
            
            // Sau đó mới xóa suất chiếu
            if ($this->showtimeModel->deleteShowtime($id)) {
                $this->showtimeModel->commit();
                $_SESSION['success_message'] = "Xóa lịch chiếu thành công!";
                header('Location: /test/Showtime/index');
                exit();
            } else {
                // Nếu xóa lịch chiếu không thành công, rollback
                $this->showtimeModel->rollback();
                $_SESSION['error_message'] = "Không thể xóa lịch chiếu.";
                header('Location: /test/Showtime/index');
                exit();
            }
        } catch (Exception $e) {
            // Xử lý lỗi và rollback transaction
            $this->showtimeModel->rollback();
            error_log("Error in ShowtimeController::delete(): " . $e->getMessage());
            $_SESSION['error_message'] = "Không thể xóa lịch chiếu: " . $e->getMessage();
            header('Location: /test/Showtime/index');
            exit();
        }
    }
}