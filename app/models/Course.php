<?php

class Course {
    private $id;
    private $title;
    private $description;
    private $teacher;
    private $students = [];

    public function __construct($id, $title, $description, $teacher) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->teacher = $teacher;
    }

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getTeacher() {
        return $this->teacher;
    }

    public function addStudent($student) {
        $this->students[] = $student;
    }

    public function getStudents() {
        return $this->students;
    }
}


?>