<?php

namespace App\Controllers;

use App\Core\Database;
use App\Models\User;

class UpdateStatusController {
    private $allowedStatuses = ['Active', 'Pending', 'Suspended'];
    
    public function index() {
        
        error_log("Controller reached");
        error_log("POST data: " . print_r($_POST, true));
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php', 'Invalid request method.');
            return;
        }

        $email = $_POST['email'] ?? null;
        $status = $_POST['status'] ?? null;

        if (!$email || !$status) {
            $this->redirect('index.php', 'Email or status is missing.');
            return;
        }

        if (!in_array($status, $this->allowedStatuses)) {
            $this->redirect('index.php', 'Invalid status value.');
            return;
        }

        try {
            $db = Database::getInstance();
            $sql = "UPDATE users SET status = :status WHERE email = :email";
            $params = ['status' => $status, 'email' => $email];
            
            $result = $db->query($sql, $params, false);
            
            if ($result) {
                $this->redirect('index.php', 'Status updated successfully!');
            } else {
                $this->redirect('index.php', 'No changes were made.');
            }
        } catch (\Exception $e) {
            $this->redirect('index.php', 'Error updating status: ' . $e->getMessage());
        }
    }

    private function redirect($path, $message) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['message'] = $message;
        header('Location: ' . $path);
        exit;
    }
}