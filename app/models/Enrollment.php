<?php
// Models/Enrollment.php
namespace App\Models;

use App\Core\Database;

class Enrollment {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function enrollStudent($studentId, $courseId) {
        // Vérifier si l'étudiant n'est pas déjà inscrit
        $checkSql = "SELECT COUNT(*) as count FROM enrollments 
                    WHERE student_id = ? AND course_id = ?";
        $result = $this->db->query($checkSql, [$studentId, $courseId]);
        
        if ($result[0]['count'] > 0) {
            throw new \Exception("L'étudiant est déjà inscrit à ce cours");
        }

        // Procéder à l'inscription
        $sql = "INSERT INTO enrollments (student_id, course_id, enrollment_date) 
                VALUES (?, ?, NOW())";
        return $this->db->query($sql, [$studentId, $courseId], false);
    }

    public function unenrollStudent($studentId, $courseId) {
        $sql = "DELETE FROM enrollments 
                WHERE student_id = ? AND course_id = ?";
        return $this->db->query($sql, [$studentId, $courseId], false);
    }

    public function getStudentEnrollments($studentId) {
        $sql = "SELECT c.* 
                FROM courses c
                JOIN enrollments e ON c.id = e.course_id 
                WHERE e.student_id = ?";
        return $this->db->query($sql, [$studentId]);
    }
}

// Controllers/EnrollmentController.php
namespace App\Controllers;

use App\Models\Enrollment;
use App\Models\Course;
use App\Core\Auth;

class EnrollmentController {
    private $enrollmentModel;
    private $courseModel;
    private $auth;

    public function __construct() {
        $this->enrollmentModel = new Enrollment();
        $this->courseModel = new Course();
        $this->auth = new Auth();
    }

    public function enroll($courseId) {
        $this->auth->requireAuth();
        
        try {
            // Vérifier si le cours existe
            $course = $this->courseModel->getCourseById($courseId);
            if (!$course) {
                $this->renderError(404, "Cours non trouvé");
                return;
            }

            $studentId = $_SESSION['user']['id'];
            $this->enrollmentModel->enrollStudent($studentId, $courseId);

            $_SESSION['success'] = "Inscription réussie au cours";
            $this->redirect('/student/courses');

        } catch (\Exception $e) {
            $_SESSION['errors'] = [$e->getMessage()];
            $this->redirect('/student/courses');
        }
    }

    public function unenroll($courseId) {
        $this->auth->requireAuth();
        
        try {
            $studentId = $_SESSION['user']['id'];
            $this->enrollmentModel->unenrollStudent($studentId, $courseId);

            $_SESSION['success'] = "Désinscription réussie";
            $this->redirect('/student/courses');

        } catch (\Exception $e) {
            $_SESSION['errors'] = [$e->getMessage()];
            $this->redirect('/student/courses');
        }
    }

    private function renderError($code, $message) {
        http_response_code($code);
        echo json_encode(['error' => $message]);
        exit;
    }

    private function redirect($path) {
        header("Location: {$path}");
        exit();
    }
}