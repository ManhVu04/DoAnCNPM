<?php
require_once __DIR__ . '/../models/Theater.php';

class TheaterController {
    private $theaterModel;

    public function __construct() {
        $this->theaterModel = new Theater();
    }

    // Hiển thị danh sách rạp
    public function index() {
        $theaters = $this->theaterModel->all();
        include __DIR__ . '/../views/theaters/index.php';
    }

    // Thêm rạp mới
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'address' => $_POST['address'],
                'city' => $_POST['city'],
                'state' => $_POST['state'],
                'zip_code' => $_POST['zip_code'],
                'phone_number' => $_POST['phone_number']
            ];
            $this->theaterModel->create($data);
            header('Location: /theaters');
        }
    }
}