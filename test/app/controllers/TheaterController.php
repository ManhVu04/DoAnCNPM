<?php
require_once 'app/models/TheaterModel.php';
require_once 'app/helpers/SessionHelper.php';

class TheaterController {
    private $theaterModel;

    public function __construct() {
        $this->theaterModel = new TheaterModel();
    }

    public function index() {
        SessionHelper::requireAdmin();
        
        $theaters = $this->theaterModel->getAllTheaters();
        include 'app/views/theater/list.php';
    }

    public function add() {
        SessionHelper::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'name' => $_POST['name'] ?? '',
                    'address' => $_POST['address'] ?? '',
                    'city' => $_POST['city'] ?? '',
                    'state' => $_POST['state'] ?? '',
                    'zip_code' => $_POST['zip_code'] ?? '',
                    'phone_number' => $_POST['phone_number'] ?? ''
                ];

                // Validate dữ liệu
                $errors = [];
                if (empty($data['name'])) $errors[] = 'Vui lòng nhập tên rạp chiếu';
                if (empty($data['address'])) $errors[] = 'Vui lòng nhập địa chỉ';
                if (empty($data['city'])) $errors[] = 'Vui lòng nhập thành phố';

                if (!empty($errors)) {
                    include 'app/views/theater/add.php';
                    return;
                }

                if ($this->theaterModel->addTheater($data)) {
                    header('Location: /test/Theater/index');
                    exit();
                } else {
                    $error = "Không thể thêm rạp chiếu mới";
                }
            } catch (Exception $e) {
                error_log("Error in TheaterController::add() - " . $e->getMessage());
                $error = "Có lỗi xảy ra khi thêm rạp chiếu mới";
            }
        }
        
        include 'app/views/theater/add.php';
    }

    public function edit($id) {
        SessionHelper::requireAdmin();
        
        $theater = $this->theaterModel->getTheaterById($id);
        if (!$theater) {
            header('Location: /test/Theater/index');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'name' => $_POST['name'] ?? $theater['name'],
                    'address' => $_POST['address'] ?? $theater['address'],
                    'city' => $_POST['city'] ?? $theater['city'],
                    'state' => $_POST['state'] ?? $theater['state'],
                    'zip_code' => $_POST['zip_code'] ?? $theater['zip_code'],
                    'phone_number' => $_POST['phone_number'] ?? $theater['phone_number']
                ];

                // Validate dữ liệu
                $errors = [];
                if (empty($data['name'])) $errors[] = 'Vui lòng nhập tên rạp chiếu';
                if (empty($data['address'])) $errors[] = 'Vui lòng nhập địa chỉ';
                if (empty($data['city'])) $errors[] = 'Vui lòng nhập thành phố';

                if (!empty($errors)) {
                    include 'app/views/theater/edit.php';
                    return;
                }

                if ($this->theaterModel->updateTheater($id, $data)) {
                    header('Location: /test/Theater/index');
                    exit();
                } else {
                    $error = "Không thể cập nhật thông tin rạp chiếu";
                }
            } catch (Exception $e) {
                error_log("Error in TheaterController::edit() - " . $e->getMessage());
                $error = "Có lỗi xảy ra khi cập nhật rạp chiếu";
            }
        }

        include 'app/views/theater/edit.php';
    }

    public function delete($id) {
        SessionHelper::requireAdmin();
        
        try {
            if ($this->theaterModel->deleteTheater($id)) {
                header('Location: /test/Theater/index');
                exit();
            } else {
                header('Location: /test/Theater/index');
                exit();
            }
        } catch (Exception $e) {
            error_log("Error in TheaterController::delete() - " . $e->getMessage());
            header('Location: /test/Theater/index');
            exit();
        }
    }

    public function show($id) {
        $theater = $this->theaterModel->getTheaterById($id);
        if (!$theater) {
            header('Location: /test/Theater/index');
            exit();
        }

        include 'app/views/theater/show.php';
    }
}