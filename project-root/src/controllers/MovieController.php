<?php
require_once __DIR__ . '/../models/Movie.php';

class MovieController {
    private $movieModel;

    public function __construct() {
        $this->movieModel = new Movie();
    }

    // Hiển thị danh sách phim
    public function index() {
        $movies = $this->movieModel->all();
        include __DIR__ . '/../views/movies/index.php';
    }

    // Hiển thị form thêm phim
    public function create() {
        include __DIR__ . '/../views/movies/create.php';
    }

    // Lưu phim mới
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'director' => $_POST['director'],
                'actors' => $_POST['actors'],
                'genre' => $_POST['genre'],
                'duration' => $_POST['duration'],
                'release_date' => $_POST['release_date'],
                'description' => $_POST['description'],
                'poster_url' => $_POST['poster_url']
            ];
            $this->movieModel->create($data);
            header('Location: /movies');
        }
    }

    // Hiển thị chi tiết phim
    public function show($id) {
        $movie = $this->movieModel->find($id);
        include __DIR__ . '/../views/movies/show.php';
    }

    // Xóa phim
    public function destroy($id) {
        $this->movieModel->delete($id);
        header('Location: /movies');
    }
}