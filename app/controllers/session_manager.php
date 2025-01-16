<?php
session_start();
include '../core/Database.php';

function validateInput($email, $password) {
    $errors = [];
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    
    return $errors;
}

function redirectToRolePage($role) {
    switch ($role) {
        case 'admin':
            header("Location: index.php");
            break;
        case 'Apprenant':
            header("Location: courses.php");
            break;
        case 'Enseignant':
            header("Location: createCourses.php");
            break;
        default:
            throw new Exception("Invalid user role");
    }
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    
    $errors = validateInput($email, $password);
    
    if (empty($errors)) {
        try {
            $db = App\Core\Database::getInstance();
            $sql = "SELECT * FROM users WHERE email = :email AND status = 'Active' LIMIT 1";
            $params = ['email' => $email];
            
            $result = $db->query($sql, $params);
            $user = $result[0] ?? null;
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'status' => $user['status']
                ];
                
                redirectToRolePage($user['role']);
            } else {
                $message = "Invalid email or password";
            }
        } catch (Exception $e) {
            $message = "System error. Please try again later.";
            error_log("Login error: " . $e->getMessage());
        }
    } else {
        $message = implode('<br>', $errors);
    }
}
?>
