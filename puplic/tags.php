<?php
session_start();

require_once '../autoload.php';
require_once '../core/Database.php';
require_once '../app/models/Tag.php';
require_once '../app/controllers/TagController.php';

use App\Models\Tag;
use App\Controllers\TagController;

$tagController = new TagController();
$message = '';
$statusClass = '';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'Admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        switch ($action) {
            case 'create':
                $tagController->createTag($_POST['name']);
                $message = 'Tag ajouté avec succès';
                $statusClass = 'bg-green-500';
                break;
            
            case 'update':
                $tagController->updateTag(
                    $_POST['id'],
                    $_POST['name']
                );
                $message = 'Tag mis à jour avec succès';
                $statusClass = 'bg-green-500';
                break;

            case 'delete':
                $tagController->deleteTag($_POST['id']);
                $message = 'Tag supprimé avec succès';
                $statusClass = 'bg-green-500';
                break;
        }
    } catch (Exception $e) {
        $message = 'Erreur: ' . $e->getMessage();
        $statusClass = 'bg-red-500';
    }
}

$tags = $tagController->getAllTags();
?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Tags - YouDemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .status-message {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            min-width: 300px;
            max-width: 80%;
            display: <?php echo $message ? 'block' : 'none'; ?>;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <?php if ($message): ?>
        <div id="statusMessage" class="status-message <?php echo $statusClass; ?> text-white p-4 rounded-md text-center font-bold">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="flex">
        <div class="w-64 h-screen bg-indigo-600 text-white">
            <div class="p-4">
                <h1 class="text-2xl font-bold">YouDemy Admin</h1>
                <ul class="mt-6">
                    <li><a href="tags.php" class="block py-2 px-4 bg-indigo-700 rounded">Gérer les Tags</a></li>
                    <li><a href="index.php" class="block py-2 px-4 hover:bg-indigo-700 rounded mt-2">Dashboard</a></li>
                    <li><a href="logout.php" class="block py-2 px-4 hover:bg-indigo-700 rounded mt-2">Déconnexion</a></li>
                </ul>
            </div>
        </div>

        
        <div class="flex-1 p-8">

            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-2xl font-bold mb-6">Liste des Tags</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-indigo-600 text-white">
                            <tr>
                                <th class="py-3 px-4 text-left">ID</th>
                                <th class="py-3 px-4 text-left">Nom</th>
                                <th class="py-3 px-4 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tags as $tag): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4"><?php echo htmlspecialchars($tag['id']); ?></td>
                                    <td class="py-3 px-4"><?php echo htmlspecialchars($tag['name']); ?></td>
                                    <td class="py-3 px-4">
                                        <button 
                                            onclick="editTag(<?php echo $tag['id']; ?>, '<?php echo htmlspecialchars($tag['name'], ENT_QUOTES); ?>')"
                                            class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 mr-2"
                                        >
                                            Modifier
                                        </button>
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $tag['id']; ?>">
                                            <button 
                                                type="submit" 
                                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce tag ?');"
                                            >
                                                Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold mb-6" id="formTitle">Ajouter un Tag</h2>
                <form id="tagForm" method="POST" class="space-y-4">
                    <input type="hidden" name="action" id="formAction" value="create">
                    <input type="hidden" name="id" id="tagId" value="">

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom du Tag</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            required 
                            class="w-full px-4 py-2 border rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>

                    <div class="flex justify-end">
                        <button 
                            type="submit" 
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700"
                        >
                            <span id="submitButtonText">Ajouter</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Handle status message
        window.onload = function() {
            var statusMessage = document.getElementById('statusMessage');
            if (statusMessage) {
                setTimeout(function() {
                    statusMessage.style.display = 'none';
                }, 3000);
            }
        }

        // Handle edit tag
        function editTag(id, name) {
            document.getElementById('formAction').value = 'update';
            document.getElementById('tagId').value = id;
            document.getElementById('name').value = name;
            document.getElementById('formTitle').textContent = 'Modifier le Tag';
            document.getElementById('submitButtonText').textContent = 'Mettre à jour';
            
            // Scroll to form
            document.getElementById('tagForm').scrollIntoView({ behavior: 'smooth' });
        }
    </script>
</body>
</html>