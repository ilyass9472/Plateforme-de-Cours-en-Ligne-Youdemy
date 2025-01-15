<?php


use App\Models\User;
class Enseiniant extends User {
    private $courses = [];

    public function addCourse($course) {
        $this->courses[] = $course;
    }

    public function getCourses() {
        return $this->courses;
    }
}












?>