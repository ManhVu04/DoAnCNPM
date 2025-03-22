<?php
require_once 'app/config/database.php';

class MovieModel {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function getAllMovies() {
        try {
            $sql = "SELECT * FROM movies ORDER BY release_date DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in MovieModel::getAllMovies() - " . $e->getMessage());
            return [];
        }
    }

    public function getMovieById($id) {
        try {
            $sql = "SELECT * FROM movies WHERE movie_id = :id LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in MovieModel::getMovieById() - " . $e->getMessage());
            return null;
        }
    }

    public function addMovie($data) {
        try {
            $sql = "INSERT INTO movies (title, director, actors, genre, duration, release_date, description, poster_url, trailer_url) 
                    VALUES (:title, :director, :actors, :genre, :duration, :release_date, :description, :poster_url, :trailer_url)";
            
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':title' => $data['title'],
                ':director' => $data['director'] ?? null,
                ':actors' => $data['actors'] ?? null,
                ':genre' => $data['genre'] ?? null,
                ':duration' => $data['duration'] ?? null,
                ':release_date' => $data['release_date'] ?? null,
                ':description' => $data['description'] ?? null,
                ':poster_url' => $data['poster_url'] ?? null,
                ':trailer_url' => $data['trailer_url'] ?? null
            ]);
        } catch (PDOException $e) {
            error_log("Error in MovieModel::addMovie() - " . $e->getMessage());
            return false;
        }
    }

    public function updateMovie($id, $data) {
        try {
            $sql = "UPDATE movies 
                    SET title = :title, 
                        director = :director,
                        actors = :actors,
                        description = :description, 
                        duration = :duration, 
                        genre = :genre, 
                        release_date = :release_date, 
                        poster_url = :poster_url,
                        trailer_url = :trailer_url
                    WHERE movie_id = :id";
            
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':title' => $data['title'],
                ':director' => $data['director'] ?? null,
                ':actors' => $data['actors'] ?? null,
                ':description' => $data['description'] ?? null,
                ':duration' => $data['duration'] ?? null,
                ':genre' => $data['genre'] ?? null,
                ':release_date' => $data['release_date'] ?? null,
                ':poster_url' => $data['poster_url'] ?? null,
                ':trailer_url' => $data['trailer_url'] ?? null
            ]);
        } catch (PDOException $e) {
            error_log("Error in MovieModel::updateMovie() - " . $e->getMessage());
            return false;
        }
    }

    public function deleteMovie($id) {
        try {
            $sql = "DELETE FROM movies WHERE movie_id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Error in MovieModel::deleteMovie() - " . $e->getMessage());
            return false;
        }
    }
} 