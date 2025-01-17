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

    
    public function create($data)
    {
        $sql = "INSERT INTO enseignants (name, email) VALUES (:name, :email)";
        $params = [
            ':name' => $data['name'],
            ':email' => $data['email'],
        ];
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

    
    public function update($id, $data)
    {
        $sql = "UPDATE enseignants SET name = :name, email = :email WHERE id = :id";
        $params = [
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':id' => $id,
        ];
        return $this->db->query($sql, $params, false);
    }

    
    public function delete($id)
    {
        $sql = "DELETE FROM enseignants WHERE id = :id";
        $params = [':id' => $id];
        return $this->db->query($sql, $params, false);
    }
}
