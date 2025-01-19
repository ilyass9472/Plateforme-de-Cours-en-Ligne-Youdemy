<?php
session_start();
use App\Controllers\CourseController;

include './core/Database.php';
require_once '../models/Course.php';
require_once './app/controllers/CourseController.php';

$controller = new CourseController();
$courses = $controller->index();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $controller->create($_POST['title'], $_POST['description'], $_POST['enseignant_id']);
    } elseif (isset($_POST['update'])) {
        $controller->update($_POST['id'], $_POST['title'], $_POST['description'], $_POST['enseignant_id']);
    } elseif (isset($_POST['delete'])) {
        $controller->delete($_POST['id']);
    }
    header("Location: coursesView.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Manage Courses</title>
</head>
<body class="bg-gray-900 text-white">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Manage Courses</h1>
        
        <table class="w-full mb-6">
            <thead>
                <tr>
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Title</th>
                    <th class="border px-4 py-2">Description</th>
                    <th class="border px-4 py-2">Enseignant ID</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $course): ?>
                <tr>
                    <td class="border px-4 py-2"><?php echo $course['id']; ?></td>
                    <td class="border px-4 py-2"><?php echo $course['title']; ?></td>
                    <td class="border px-4 py-2"><?php echo $course['description']; ?></td>
                    <td class="border px-4 py-2"><?php echo $course['Enseiniant_id']; ?></td>
                    <td class="border px-4 py-2">
                        <form method="post" class="inline-block">
                            <input type="hidden" name="id" value="<?php echo $course['id']; ?>">
                            <input type="submit" name="delete" value="Delete" class="bg-red-500 text-white px-4 py-2 rounded">
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2 class="text-2xl font-bold mb-4">Add/Edit Course</h2>
        <form method="post" class="bg-gray-800 p-4 rounded-lg">
            <input type="hidden" name="id" placeholder="ID" class="mb-2 p-2 rounded">
            <input type="text" name="title" placeholder="Title" class="mb-2 p-2 w-full rounded">
            <textarea name="description" placeholder="Description" class="mb-2 p-2 w-full rounded"></textarea>
            <input type="text" name="enseignant_id" placeholder="Enseignant ID" class="mb-2 p-2 rounded">
            <button type="submit" name="add" class="bg-green-500 px-4 py-2 rounded">Add Course</button>
        </form>
    </div>
</body>
</html>
