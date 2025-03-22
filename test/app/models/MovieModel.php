<?php
require_once 'app/config/database.php';

class MovieModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllMovies() {
        try {
            $this->db->query("SELECT * FROM movies ORDER BY release_date DESC");
            return $this->db->resultSet();
        } catch (PDOException $e) {
            error_log("Error in MovieModel::getAllMovies() - " . $e->getMessage());
            return [];
        }
    }

    public function getMovieById($id) {
        try {
            $this->db->query("SELECT * FROM movies WHERE movie_id = :id LIMIT 1");
            $this->db->bind(':id', $id);
            return $this->db->single();
        } catch (PDOException $e) {
            error_log("Error in MovieModel::getMovieById() - " . $e->getMessage());
            return null;
        }
    }

    public function addMovie($data) {
        try {
            $this->db->query("INSERT INTO movies (title, director, actors, genre, duration, release_date, description, poster_url, trailer_url) 
                    VALUES (:title, :director, :actors, :genre, :duration, :release_date, :description, :poster_url, :trailer_url)");
            
            $this->db->bind(':title', $data['title']);
            $this->db->bind(':director', $data['director'] ?? null);
            $this->db->bind(':actors', $data['actors'] ?? null);
            $this->db->bind(':genre', $data['genre'] ?? null);
            $this->db->bind(':duration', $data['duration'] ?? null);
            $this->db->bind(':release_date', $data['release_date'] ?? null);
            $this->db->bind(':description', $data['description'] ?? null);
            $this->db->bind(':poster_url', $data['poster_url'] ?? null);
            $this->db->bind(':trailer_url', $data['trailer_url'] ?? null);
            
            return $this->db->execute();
        } catch (PDOException $e) {
            error_log("Error in MovieModel::addMovie() - " . $e->getMessage());
            return false;
        }
    }

    public function updateMovie($id, $data) {
        try {
            $this->db->query("UPDATE movies 
                    SET title = :title, 
                        director = :director,
                        actors = :actors,
                        description = :description, 
                        duration = :duration, 
                        genre = :genre, 
                        release_date = :release_date, 
                        poster_url = :poster_url,
                        trailer_url = :trailer_url
                    WHERE movie_id = :id");
            
            $this->db->bind(':id', $id);
            $this->db->bind(':title', $data['title']);
            $this->db->bind(':director', $data['director'] ?? null);
            $this->db->bind(':actors', $data['actors'] ?? null);
            $this->db->bind(':description', $data['description'] ?? null);
            $this->db->bind(':duration', $data['duration'] ?? null);
            $this->db->bind(':genre', $data['genre'] ?? null);
            $this->db->bind(':release_date', $data['release_date'] ?? null);
            $this->db->bind(':poster_url', $data['poster_url'] ?? null);
            $this->db->bind(':trailer_url', $data['trailer_url'] ?? null);
            
            return $this->db->execute();
        } catch (PDOException $e) {
            error_log("Error in MovieModel::updateMovie() - " . $e->getMessage());
            return false;
        }
    }

    public function deleteMovie($id) {
        try {
            $this->db->query("DELETE FROM movies WHERE movie_id = :id");
            $this->db->bind(':id', $id);
            return $this->db->execute();
        } catch (PDOException $e) {
            error_log("Error in MovieModel::deleteMovie() - " . $e->getMessage());
            return false;
        }
    }
} 