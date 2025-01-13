<?php

namespace App\Models;

use Core\Database;

class Course {
    public static function getAllCourses() {
        $db = Database::getInstance();
        $sql = "SELECT * FROM courses";
        return $db->query($sql);
    }
}


?>