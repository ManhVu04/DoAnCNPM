<?php
require_once 'app/models/ScreenModel.php';
require_once 'app/models/TheaterModel.php';
require_once 'app/helpers/SessionHelper.php';

class ScreenController {
    private $screenModel;
    private $theaterModel;

    public function __construct() {
        $this->screenModel = new ScreenModel();
        $this->theaterModel = new TheaterModel();
    }

    public function index() {
        SessionHelper::requireAdmin();
        
        $screens = $this->screenModel->getAllScreens();
        include 'app/views/screen/list.php';
    }

    public function add() {
        SessionHelper::requireAdmin();
        
        $theaters = $this->theaterModel->getAllTheaters();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'theater_id' => $_POST['theater_id'] ?? '',
                    'screen_number' => $_POST['screen_number'] ?? '',
                    'capacity' => $_POST['capacity'] ?? '',
                    'screen_type' => $_POST['screen_type'] ?? ''
                ];

                // Validate dữ liệu
                $errors = [];
                if (empty($data['theater_id'])) $errors[] = 'Vui lòng chọn rạp chiếu';
                if (empty($data['screen_number'])) $errors[] = 'Vui lòng nhập số phòng chiếu';
                if (empty($data['capacity']) || !is_numeric($data['capacity'])) $errors[] = 'Vui lòng nhập sức chứa hợp lệ';

                if (!empty($errors)) {
                    include 'app/views/screen/add.php';
                    return;
                }

                if ($this->screenModel->addScreen($data)) {
                    header('Location: /test/Screen/index');
                    exit();
                } else {
                    $error = "Không thể thêm phòng chiếu mới";
                }
            } catch (Exception $e) {
                error_log("Error in ScreenController::add() - " . $e->getMessage());
                $error = "Có lỗi xảy ra khi thêm phòng chiếu mới";
            }
        }
        
        include 'app/views/screen/add.php';
    }

    public function edit($id) {
        SessionHelper::requireAdmin();
        
        $screen = $this->screenModel->getScreenById($id);
        if (!$screen) {
            header('Location: /test/Screen/index');
            exit();
        }
        
        $theaters = $this->theaterModel->getAllTheaters();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'theater_id' => $_POST['theater_id'] ?? $screen['theater_id'],
                    'screen_number' => $_POST['screen_number'] ?? $screen['screen_number'],
                    'capacity' => $_POST['capacity'] ?? $screen['capacity'],
                    'screen_type' => $_POST['screen_type'] ?? $screen['screen_type']
                ];

                // Validate dữ liệu
                $errors = [];
                if (empty($data['theater_id'])) $errors[] = 'Vui lòng chọn rạp chiếu';
                if (empty($data['screen_number'])) $errors[] = 'Vui lòng nhập số phòng chiếu';
                if (empty($data['capacity']) || !is_numeric($data['capacity'])) $errors[] = 'Vui lòng nhập sức chứa hợp lệ';

                if (!empty($errors)) {
                    include 'app/views/screen/edit.php';
                    return;
                }

                if ($this->screenModel->updateScreen($id, $data)) {
                    header('Location: /test/Screen/index');
                    exit();
                } else {
                    $error = "Không thể cập nhật thông tin phòng chiếu";
                }
            } catch (Exception $e) {
                error_log("Error in ScreenController::edit() - " . $e->getMessage());
                $error = "Có lỗi xảy ra khi cập nhật phòng chiếu";
            }
        }

        include 'app/views/screen/edit.php';
    }

    public function delete($id) {
        SessionHelper::requireAdmin();
        
        try {
            if ($this->screenModel->deleteScreen($id)) {
                header('Location: /test/Screen/index');
                exit();
            } else {
                header('Location: /test/Screen/index');
                exit();
            }
        } catch (Exception $e) {
            error_log("Error in ScreenController::delete() - " . $e->getMessage());
            header('Location: /test/Screen/index');
            exit();
        }
    }

    public function show($id) {
        $screen = $this->screenModel->getScreenById($id);
        if (!$screen) {
            header('Location: /test/Screen/index');
            exit();
        }

        include 'app/views/screen/show.php';
    }
}