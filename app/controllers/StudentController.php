<?php

namespace App\Controllers;

use App\Models\Course;
use App\Core\Auth;
use App\Core\View;

class StudentController {
    private $courseModel;
    private $auth;
    private $view;

    public function __construct() {
        $this->courseModel = new Course();
        $this->auth = new Auth();
        $this->view = new View();
    }

    
    public function catalog() {
        $search = $_GET['search'] ?? '';
        $filter = $_GET['filter'] ?? '';

        $courses = $this->courseModel->searchCourses($search, $filter);
        
        $this->view->render('student/catalog', [
            'courses' => $courses,
            'search' => $search,
            'filter' => $filter
        ]);
    }

    
    public function courseDetails($id) {
        $course = $this->courseModel->getCourseWithDetails($id);
        
        if (!$course) {
            $this->view->renderError(404, 'Cours non trouvé');
        }

        $this->view->render('student/course-details', [
            'course' => $course
        ]);
    }

    
    public function enroll($courseId) {
        $this->auth->requireAuth();
        
        $userId = $this->auth->getUserId();
        
        if ($this->courseModel->isEnrolled($userId, $courseId)) {
            $this->view->renderError(400, 'Vous êtes déjà inscrit à ce cours');
        }

        try {
            $this->courseModel->enrollStudent($userId, $courseId);
            $this->view->redirect('/student/my-courses', ['success' => 'Inscription réussie !']);
        } catch (\Exception $e) {
            $this->view->renderError(500, 'Erreur lors de l\'inscription');
        }
    }

    
    public function myCourses() {
        $this->auth->requireAuth();
        
        $userId = $this->auth->getUserId();
        $courses = $this->courseModel->getStudentCourses($userId);
        
        $this->view->render('student/my-courses', [
            'courses' => $courses
        ]);
    }
}