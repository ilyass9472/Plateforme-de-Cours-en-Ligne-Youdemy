<?php
namespace App\Controllers;

use App\Models\Apprenant;
use Exception;

class ApprenantController {
    private $apprenantModel;

    public function __construct() {
        $this->apprenantModel = new Apprenant();
    }

    
    public function listAvailableCourses() {
        try {
            $courses = $this->apprenantModel->getAllCourses();
            return [
                'success' => true,
                'data' => $courses
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    
    public function listEnrolledCourses($studentId) {
        try {
            $courses = $this->apprenantModel->getEnrolledCourses($studentId);
            return [
                'success' => true,
                'data' => $courses
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    
    public function enrollCourse($studentId, $courseId) {
        try {
            $this->apprenantModel->enrollToCourse($studentId, $courseId);
            return [
                'success' => true,
                'message' => 'Inscription au cours réussie'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    
    public function unenrollCourse($studentId, $courseId) {
        try {
            $this->apprenantModel->unenrollFromCourse($studentId, $courseId);
            return [
                'success' => true,
                'message' => 'Désinscription du cours réussie'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    
    public function getCourseInfo($studentId, $courseId) {
        try {
            $courseDetails = $this->apprenantModel->getCourseDetails($courseId);
            $progress = $this->apprenantModel->getCourseProgress($studentId, $courseId);
            
            return [
                'success' => true,
                'data' => [
                    'course' => $courseDetails,
                    'progress' => $progress
                ]
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}