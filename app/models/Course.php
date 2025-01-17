<?php

namespace App\Models;

use App\Core\Database;

class Course
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    
    public function create($data)
    {
        $sql = "INSERT INTO courses (title, description) VALUES (:title, :description)";
        $params = [
            ':title' => $data['title'],
            ':description' => $data['description'],
        ];
        return $this->db->query($sql, $params, false);
    }

    
    public function getAll()
    {
        $sql = "SELECT * FROM courses";
        return $this->db->query($sql);
    }

    
    public function getById($id)
    {
        $sql = "SELECT * FROM courses WHERE id = :id";
        $params = [':id' => $id];
        $result = $this->db->query($sql, $params);
        return $result ? $result[0] : null;
    }

    
    public function update($id, $data)
    {
        $sql = "UPDATE courses SET title = :title, description = :description WHERE id = :id";
        $params = [
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':id' => $id,
        ];
        return $this->db->query($sql, $params, false);
    }

    
    public function delete($id)
    {
        $sql = "DELETE FROM courses WHERE id = :id";
        $params = [':id' => $id];
        return $this->db->query($sql, $params, false);
    }
}
