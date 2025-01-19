<?php
namespace App\Models;

use App\Core\Database;

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Créer un utilisateur
    public function createUser($name, $email, $password, $role) {
        $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        return $this->db->query($sql, [$name, $email, password_hash($password, PASSWORD_DEFAULT), $role], false);
    }

    // Obtenir tous les utilisateurs
    public function getAllUsers() {
        $sql = "SELECT * FROM users";
        return $this->db->query($sql);
    }

    // Obtenir un utilisateur par ID
    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        return $this->db->query($sql, [$id])[0] ?? null;
    }

    // Mettre à jour un utilisateur
    public function updateUser($id, $name, $email, $password, $role) {
        $sql = "UPDATE users SET name = ?, email = ?, password = ?, role = ? WHERE id = ?";
        return $this->db->query($sql, [$name, $email, password_hash($password, PASSWORD_DEFAULT), $role, $id], false);
    }

    // Supprimer un utilisateur
    public function deleteUser($id) {
        $sql = "DELETE FROM users WHERE id = ?";
        return $this->db->query($sql, [$id], false);
    }
}
