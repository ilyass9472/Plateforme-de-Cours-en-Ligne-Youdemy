<?php


namespace App\Models;
use App\Core\Database;

class Course {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    
    public function getUserCourses($userId) {
        $sql = "SELECT c.*, u.name as instructor_name, 
                COALESCE(up.progress, 0) as progress 
                FROM courses c
                LEFT JOIN users u ON c.Enseiniant_id = u.id
                LEFT JOIN user_progress up ON c.id = up.course_id AND up.user_id = ?
                WHERE c.id IN (
                    SELECT course_id FROM enrollments WHERE user_id = ?
                )";
        return $this->db->query($sql, [$userId, $userId]);
    }

    
    public function createCourse($title, $description, $enseignantId) {
        $sql = "INSERT INTO courses (title, description, Enseiniant_id) VALUES (?, ?, ?)";
        return $this->db->query($sql, [$title, $description, $enseignantId], false);
    }

    public function getAllCourses() {
        $sql = "SELECT * FROM courses";
        return $this->db->query($sql);
    }

    public function getCourseById($id) {
        $sql = "SELECT * FROM courses WHERE id = ?";
        return $this->db->query($sql, [$id])[0] ?? null;
    }

    public function updateCourse($id, $title, $description, $enseignantId) {
        $sql = "UPDATE courses SET title = ?, description = ?, Enseiniant_id = ? WHERE id = ?";
        return $this->db->query($sql, [$title, $description, $enseignantId, $id], false);
    }

    public function deleteCourse($id) {
        $sql = "DELETE FROM courses WHERE id = ?";
        return $this->db->query($sql, [$id], false);
    }
}
