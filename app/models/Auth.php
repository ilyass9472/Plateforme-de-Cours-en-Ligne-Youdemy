<?php
namespace App\Core;

use PDO;
use Exception;

class Auth {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function login(string $email, string $password): array {
        try {
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'message' => 'Email invalide'];
            }
            
            if (empty($password)) {
                return ['success' => false, 'message' => 'Mot de passe requis'];
            }
            
            
            $sql = "SELECT * FROM users WHERE email = :email AND status = 'active' LIMIT 1";
            $result = $this->db->query($sql, ['email' => $email]);
            
            if (empty($result)) {
                $this->logFailedAttempt($email);
                return ['success' => false, 'message' => 'Email ou mot de passe incorrect'];
            }
            
            $user = $result[0];
            
            
            if ($password === $user['password']) {
                $this->logSuccessfulLogin($user['id']);
                
                
                $sessionData = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'status' => $user['status']
                ];
                
                return [
                    'success' => true,
                    'user' => $sessionData,
                    'redirect' => $this->getRedirectUrl($user['role'])
                ];
            }
            
            $this->logFailedAttempt($email);
            return ['success' => false, 'message' => 'Email ou mot de passe incorrect'];
            
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erreur système, veuillez réessayer plus tard'];
        }
    }
    
    private function getRedirectUrl(string $role): string {
        $redirects = [
            'Admin' => 'index.php',
            'Apprenant' => 'course_view.php',
            'Enseignant' => 'createCourses.php'
        ];
        
        return $redirects[$role] ?? 'index.php';
    }
    
    private function logSuccessfulLogin(int $userId): void {
        $sql = "INSERT INTO login_logs (user_id, status, ip_address) VALUES (:user_id, 'success', :ip)";
        $params = [
            'user_id' => $userId,
            'ip' => $_SERVER['REMOTE_ADDR']
        ];
        $this->db->query($sql, $params, false);
    }
    
    private function logFailedAttempt(string $email): void {
        $sql = "INSERT INTO login_logs (email, status, ip_address) VALUES (:email, 'failed', :ip)";
        $params = [
            'email' => $email,
            'ip' => $_SERVER['REMOTE_ADDR']
        ];
        $this->db->query($sql, $params, false);
    }
    
    public function logout(): void {
        session_destroy();
        session_start();
    }
}