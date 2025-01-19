<?php
namespace App\Controllers;
use App\Models\Course;
class CourseController {
    private $model;

    public function __construct() {
        $this->model = new Course();
    }

    public function index() {
        return $this->model->getAllCourses();
    }

    public function create($title, $description, $enseignantId) {
        return $this->model->createCourse($title, $description, $enseignantId);
    }

    public function update($id, $title, $description, $enseignantId) {
        return $this->model->updateCourse($id, $title, $description, $enseignantId);
    }

    public function delete($id) {
        return $this->model->deleteCourse($id);
    }
}
