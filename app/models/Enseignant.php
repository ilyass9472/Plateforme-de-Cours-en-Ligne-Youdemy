<?php

namespace App\Models;
use App\Core\Database;

class Enseignant
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    
    public function index()
    {
        $sql = "SELECT * FROM courses";
        return $this->db->query($sql);
    }
    
    public function create($title, $description, $enseignantId)
{
    $sql = "INSERT INTO courses (title, description, Enseiniant_id) VALUES (:title, :description, :enseignant_id)";
    $params = [
        ':title' => $title,
        ':description' => $description,
        ':enseignant_id' => $enseignantId
    ];
    return $this->db->query($sql, $params, false);
}
    
    public function update($id, $title, $description)
    {
        $sql = "UPDATE courses SET title = :title, description = :description WHERE id = :id";
        $params = [
            ':id' => $id,
            ':title' => $title,
            ':description' => $description
        ];
        return $this->db->query($sql, $params, false);
    }
    
    
    public function delete($id)
    {
        $sql = "DELETE FROM courses WHERE id = :id";
        $params = [':id' => $id];
        return $this->db->query($sql, $params, false);
    }

    
    public function getAll()
    {
        $sql = "SELECT * FROM enseignants";
        return $this->db->query($sql);
    }
    
    public function getById($id)
    {
        $sql = "SELECT * FROM enseignants WHERE id = :id";
        $params = [':id' => $id];
        $result = $this->db->query($sql, $params);
        return $result ? $result[0] : null;
    }
}