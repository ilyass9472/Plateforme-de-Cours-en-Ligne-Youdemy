<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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

function isAuthenticated() {
    return isset($_SESSION['user']);
}

function requireLogin() {
    if (!isAuthenticated()) {
        header("Location: login.php");
        exit();
    }
}

function hasRole($role) {
    return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == $role;
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
?>
