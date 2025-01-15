<?php

namespace App\Models;

use App\Core\Database;

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function updateStatus($email, $status) {
        $sql = "UPDATE users SET status = :status WHERE email = :email";
        return $this->db->query($sql, ['status' => $status, 'email' => $email], false);
    }
}
