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
            $data = [
                'movie_id' => $_POST['movie_id'],
                'screen_id' => $_POST['screen_id'],
                'show_date' => $_POST['show_date'],
                'show_time' => $_POST['show_time'],
                'ticket_price' => $_POST['ticket_price']
            ];
            
            if ($this->showtimeModel->addShowtime($data)) {
                // Lấy ID của showtime vừa tạo
                $showtimeId = $this->showtimeModel->getLastInsertId();
                
                // Tạo vé cho suất chiếu
                $this->ticketModel->generateTicketsForShowtime($showtimeId, $data['screen_id']);
                
                header('Location: /test/Showtime/index');
                exit();
            } else {
                // Xử lý lỗi
                $movies = $this->movieModel->getAllMovies();
                $screens = $this->screenModel->getAllScreens();
                $error = "Không thể tạo lịch chiếu. Vui lòng thử lại.";
                include 'app/views/showtime/create.php';
            }
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
                header('Location: /test/Showtime/index');
                exit();
            } else {
                // Xử lý lỗi
                $showtime = $this->showtimeModel->getShowtimeById($id);
                $movies = $this->movieModel->getAllMovies();
                $screens = $this->screenModel->getAllScreens();
                $error = "Không thể cập nhật lịch chiếu. Vui lòng thử lại.";
                include 'app/views/showtime/edit.php';
            }
        }
    }
    
    // Xóa lịch chiếu
    public function delete($id) {
        SessionHelper::requireAdmin();
        
        if ($this->showtimeModel->deleteShowtime($id)) {
            header('Location: /test/Showtime/index');
            exit();
        } else {
            // Xử lý lỗi
            $error = "Không thể xóa lịch chiếu.";
            $showtimes = $this->showtimeModel->getAllShowtimes();
            include 'app/views/showtime/index.php';
        }
    }
}