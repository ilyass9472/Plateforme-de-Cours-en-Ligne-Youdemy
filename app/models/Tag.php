<?php
namespace App\Models;

use App\Core\Database;

class Tag {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function createTag($name) {
        $sql = "INSERT INTO tags (name) VALUES (?)";
        return $this->db->query($sql, [$name], false);
    }

    public function getAllTags() {
        $sql = "SELECT * FROM tags";
        return $this->db->query($sql);
    }

    public function getTagById($id) {
        $sql = "SELECT * FROM tags WHERE id = ?";
        return $this->db->query($sql, [$id])[0] ?? null;
    }

    
    public function updateTag($id, $name) {
        $sql = "UPDATE tags SET name = ? WHERE id = ?";
        return $this->db->query($sql, [$name, $id], false);
    }

    
    public function deleteTag($id) {
        $sql = "DELETE FROM tags WHERE id = ?";
        return $this->db->query($sql, [$id], false);
    }
}
