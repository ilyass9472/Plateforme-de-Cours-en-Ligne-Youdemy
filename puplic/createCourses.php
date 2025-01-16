<?php
session_start();
require_once '../core/Database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'Enseignant') {
    header('Location: login.php');
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';

    if (empty($title) || empty($description)) {
        $message = 'Tous les champs sont obligatoires.';
    } else {
        try {
            $db = App\Core\Database::getInstance();
            $sql = "INSERT INTO courses (title, description, teacher_id) VALUES (:title, :description, :teacher_id)";
            $params = [
                'title' => $title,
                'description' => $description,
                'teacher_id' => $_SESSION['user']['id']
            ];
            $db->query($sql, $params);
            $message = 'Cours ajouté avec succès.';
        } catch (Exception $e) {
            $message = 'Erreur lors de l\'ajout du cours.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Course - YouDemy</title>
</head>
<body>
    <h1>Create a New Course</h1>
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST">
        <div>
            <label for="title">Course Title:</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div>
            <label for="description">Course Description:</label>
            <textarea id="description" name="description" required></textarea>
        </div>
        <button type="submit">Create Course</button>
    </form>
</body>
</html>
