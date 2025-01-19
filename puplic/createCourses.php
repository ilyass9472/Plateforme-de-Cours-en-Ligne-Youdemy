<?php
session_start();
require_once '../autoload.php';

require_once '../app/controllers/EnseignantController.php';
require_once '../app/controllers/sessionManager.php';

use App\Controllers\EnseignantController;
use App\Controllers\SessionManager;


// SessionManager::checkRole('Enseignant');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'Enseignant') {
    header('Location: login.php');
    exit();
}

$controller = new EnseignantController();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        switch ($action) {
            case 'create':
                $controller->create($_POST['title'], $_POST['description'], $_SESSION['user']['id']);
                $message = 'Cours ajouté avec succès';
                break;
            case 'update':
                $controller->update($_POST['id'], $_POST['title'], $_POST['description']);
                $message = 'Cours mis à jour avec succès';
                break;
            case 'delete':
                $controller->delete($_POST['id']);
                $message = 'Cours supprimé avec succès';
                break;
        }
    } catch (Exception $e) {
        $message = 'Erreur: ' . $e->getMessage();
    }
}

$courses = $controller->index();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Gérer les Cours</title>
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Gérer les Cours</h1>

        <?php if (!empty($message)): ?>
            <div class="bg-blue-500 text-white p-2 mb-4 rounded">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <table class="w-full mb-6 border-collapse">
            <thead>
                <tr>
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Titre</th>
                    <th class="border px-4 py-2">Description</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($course['id']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($course['title']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($course['description']); ?></td>
                        <td class="border px-4 py-2">
                            <form method="post" class="inline-block">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($course['id']); ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Supprimer</button>
                            </form>
                            <form method="post" class="inline-block">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($course['id']); ?>">
                                <input type="hidden" name="action" value="update">
                                <button type="button" class="bg-yellow-500 text-white px-4 py-2 rounded" onclick="populateForm('<?php echo $course['id']; ?>', '<?php echo $course['title']; ?>', '<?php echo $course['description']; ?>')">Modifier</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2 class="text-2xl font-bold mb-4">Ajouter / Modifier un Cours</h2>
        <form method="post" id="courseForm" class="bg-white p-4 rounded shadow">
            <input type="hidden" name="id" id="courseId">
            <input type="hidden" name="action" id="formAction" value="create">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Titre</label>
                <input type="text" name="title" id="title" class="mt-1 block w-full border rounded p-2">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" class="mt-1 block w-full border rounded p-2"></textarea>
            </div>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Enregistrer</button>
        </form>
    </div>

    <script>
        function populateForm(id, title, description) {
            document.getElementById('courseId').value = id;
            document.getElementById('title').value = title;
            document.getElementById('description').value = description;
            document.getElementById('formAction').value = 'update';
        }
    </script>
</body>
</html>
