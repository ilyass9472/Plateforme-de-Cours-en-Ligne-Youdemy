<?php

require_once '../models/User.php';
require_once './core/Database.php';

class UserController {

    public function getUsers() {
        $db = Database::getInstance();
        $sql = "SELECT * FROM users";
        $users = $db->query($sql);
        return $users;
    }

    public function updateUserStatus($userId, $status) {
        $db = Database::getInstance();
        $sql = "UPDATE users SET status = :status WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $userId);
        return $stmt->execute();
    }
}
?>