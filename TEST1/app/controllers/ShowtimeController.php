<?php
require_once __DIR__ . '/../models/Showtime.php';
require_once __DIR__ . '/../models/Movie.php';
require_once __DIR__ . '/../models/Screen.php';

class ShowtimeController {
    private $showtimeModel;
    private $movieModel;
    private $screenModel;

    public function __construct() {
        $this->showtimeModel = new Showtime();
        $this->movieModel = new Movie();
        $this->screenModel = new Screen();
    }

    // Hiển thị danh sách lịch chiếu
    public function index() {
        $showtimes = $this->showtimeModel->all();
        include __DIR__ . '/../views/showtimes/index.php';
    }

    // Hiển thị form thêm lịch chiếu
    public function create() {
        $movies = $this->movieModel->all();
        $screens = $this->screenModel->all();
        include __DIR__ . '/../views/showtimes/create.php';
    }

    // Lưu lịch chiếu mới
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'movie_id' => $_POST['movie_id'],
                'screen_id' => $_POST['screen_id'],
                'show_date' => $_POST['show_date'],
                'show_time' => $_POST['show_time']
            ];
            $this->showtimeModel->create($data);
            header('Location: /showtimes');
        }
    }
}