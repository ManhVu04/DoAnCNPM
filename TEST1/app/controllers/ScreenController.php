<?php
require_once __DIR__ . '/../models/Screen.php';
require_once __DIR__ . '/../models/Theater.php';

class ScreenController {
    private $screenModel;
    private $theaterModel;

    public function __construct() {
        $this->screenModel = new Screen();
        $this->theaterModel = new Theater();
    }

    // Hiển thị danh sách phòng chiếu
    public function index() {
        $screens = $this->screenModel->all();
        include __DIR__ . '/../views/screens/index.php';
    }

    // Hiển thị form thêm phòng chiếu
    public function create() {
        $theaters = $this->theaterModel->all();
        include __DIR__ . '/../views/screens/create.php';
    }

    // Lưu phòng chiếu mới
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'theater_id' => $_POST['theater_id'],
                'screen_number' => $_POST['screen_number'],
                'capacity' => $_POST['capacity'],
                'screen_type' => $_POST['screen_type']
            ];
            $this->screenModel->create($data);
            header('Location: /screens');
        }
    }
}