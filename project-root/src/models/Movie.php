<?php
require_once __DIR__ . '/Model.php';

class Movie extends Model {
    protected $table = 'movies';

    // Thêm phim mới
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (title, director, actors, genre, duration, release_date, description, poster_url) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['director'],
            $data['actors'],
            $data['genre'],
            $data['duration'],
            $data['release_date'],
            $data['description'],
            $data['poster_url']
        ]);
    }

    // Cập nhật phim
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET 
                title = ?, director = ?, actors = ?, genre = ?, duration = ?, 
                release_date = ?, description = ?, poster_url = ? 
                WHERE movie_id = ?";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['director'],
            $data['actors'],
            $data['genre'],
            $data['duration'],
            $data['release_date'],
            $data['description'],
            $data['poster_url'],
            $id
        ]);
    }
}