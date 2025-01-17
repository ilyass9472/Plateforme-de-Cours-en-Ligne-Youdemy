<?php

session_start();


use App\Controllers\EnseignantController;

require_once '../core/Database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'Apprenant') {
    header('Location: login.php');
    exit();
}

$db = App\Core\Database::getInstance();
$sql = "SELECT * FROM courses";
$courses = $db->query($sql);


$controller = new EnseignantController();
$enseignants = $controller->listEnseignants();
foreach ($enseignants as $enseignant) {
    echo "<p>{$enseignant['id']}: {$enseignant['name']} - {$enseignant['email']}</p>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses - YouDemy</title>
</head>
<body>
    <h1>Courses Available</h1>
    <ul>
        <?php foreach ($courses as $course): ?>
            <li><?php echo htmlspecialchars($course['title']); ?> - <?php echo htmlspecialchars($course['description']); ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
