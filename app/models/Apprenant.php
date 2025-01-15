<?php 

use App\Models\User;
class Apprenant extends User {
    private $enrolledCourses = [];

    public function enrollInCourse($course) {
        $this->enrolledCourses[] = $course;
    }

    public function getEnrolledCourses() {
        return $this->enrolledCourses;
    }
}




?>