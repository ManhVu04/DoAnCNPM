<?php
require_once('app/helpers/SessionHelper.php');
require_once('app/config/database.php');
require_once('app/models/MovieModel.php');

class MovieController {
    private $movieModel;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->movieModel = new MovieModel($this->db);
    }

    public function index() {
        // Chuyển hướng đến action list
        $this->list();
    }

    public function list() {
        try {
            // Lấy danh sách phim từ model
            $movies = $this->movieModel->getAllMovies();
            
            // Load view với dữ liệu phim
            include 'app/views/movie/list.php';
        } catch (Exception $e) {
            // Ghi log lỗi
            error_log("Error in MovieController::list() - " . $e->getMessage());
            
            // Hiển thị thông báo lỗi cho người dùng
            $error = "Có lỗi xảy ra khi tải danh sách phim";
            $movies = [];
            include 'app/views/movie/list.php';
        }
    }

    public function add() {
        SessionHelper::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'title' => $_POST['title'] ?? '',
                    'director' => $_POST['director'] ?? null,
                    'actors' => $_POST['actors'] ?? null,
                    'description' => $_POST['description'] ?? null,
                    'duration' => !empty($_POST['duration']) ? intval($_POST['duration']) : null,
                    'genre' => $_POST['genre'] ?? null,
                    'release_date' => $_POST['release_date'] ?? null,
                    'poster_url' => '',
                    'trailer_url' => $_POST['trailer_url'] ?? null
                ];

                // Xử lý upload poster
                if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = 'uploads/posters/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $fileName = uniqid() . '_' . basename($_FILES['poster']['name']);
                    $targetPath = $uploadDir . $fileName;

                    if (move_uploaded_file($_FILES['poster']['tmp_name'], $targetPath)) {
                        $data['poster_url'] = $targetPath;
                    }
                }

                if ($this->movieModel->addMovie($data)) {
                    header('Location: /test/Movie/list');
                    exit();
                } else {
                    $error = "Không thể thêm phim mới";
                }
            } catch (Exception $e) {
                error_log("Error in MovieController::add() - " . $e->getMessage());
                $error = "Có lỗi xảy ra khi thêm phim mới";
            }
        }
        
        include 'app/views/movie/add.php';
    }

    public function edit($id) {
        SessionHelper::requireAdmin();
        
        $movie = $this->movieModel->getMovieById($id);
        if (!$movie) {
            header('Location: /test/Movie/list');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'title' => $_POST['title'] ?? $movie['title'],
                    'director' => $_POST['director'] ?? $movie['director'],
                    'actors' => $_POST['actors'] ?? $movie['actors'],
                    'description' => $_POST['description'] ?? $movie['description'],
                    'duration' => !empty($_POST['duration']) ? intval($_POST['duration']) : $movie['duration'],
                    'genre' => $_POST['genre'] ?? $movie['genre'],
                    'release_date' => $_POST['release_date'] ?? $movie['release_date'],
                    'poster_url' => $movie['poster_url'],
                    'trailer_url' => $_POST['trailer_url'] ?? $movie['trailer_url']
                ];

                // Xử lý upload poster mới
                if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = 'uploads/posters/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $fileName = uniqid() . '_' . basename($_FILES['poster']['name']);
                    $targetPath = $uploadDir . $fileName;

                    if (move_uploaded_file($_FILES['poster']['tmp_name'], $targetPath)) {
                        // Xóa poster cũ nếu có
                        if (!empty($movie['poster_url']) && file_exists($movie['poster_url'])) {
                            unlink($movie['poster_url']);
                        }
                        $data['poster_url'] = $targetPath;
                    }
                }

                if ($this->movieModel->updateMovie($id, $data)) {
                    header('Location: /test/Movie/list');
                    exit();
                } else {
                    $error = "Không thể cập nhật thông tin phim";
                }
            } catch (Exception $e) {
                error_log("Error in MovieController::edit() - " . $e->getMessage());
                $error = "Có lỗi xảy ra khi cập nhật phim";
            }
        }

        include 'app/views/movie/edit.php';
    }

    public function delete($id) {
        SessionHelper::requireAdmin();
        
        try {
            $movie = $this->movieModel->getMovieById($id);
            if ($movie) {
                // Xóa poster nếu có
                if (!empty($movie['poster_url']) && file_exists($movie['poster_url'])) {
                    unlink($movie['poster_url']);
                }

                if ($this->movieModel->deleteMovie($id)) {
                    header('Location: /test/Movie/list');
                    exit();
                }
            }
        } catch (Exception $e) {
            error_log("Error in MovieController::delete() - " . $e->getMessage());
        }
        
        header('Location: /test/Movie/list');
        exit();
    }

    public function show($id) {
        try {
            $movie = $this->movieModel->getMovieById($id);
            if ($movie) {
                include 'app/views/movie/show.php';
            } else {
                header('Location: /test/Movie/list');
                exit();
            }
        } catch (Exception $e) {
            error_log("Error in MovieController::show() - " . $e->getMessage());
            header('Location: /test/Movie/list');
            exit();
        }
    }
}