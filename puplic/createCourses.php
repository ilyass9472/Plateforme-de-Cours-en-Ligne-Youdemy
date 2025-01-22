<?php
session_start();
require_once '../autoload.php';
require_once '../app/models/Enseignant.php';
require_once '../app/controllers/EnseignantController.php';
require_once '../app/controllers/sessionManager.php';

// use App\Controllers\EnseignantController;
// use App\Controllers\SessionManager;

use App\Models\Enseignant;
if (!isset($_SESSION['user']) || 
    ($_SESSION['user']['role'] != 'Enseignant' && $_SESSION['user']['role'] != 'Admin')) {
    header('Location: login.php');
    exit();
}

$enseignant = new Enseignant();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        switch ($action) {
            case 'create':
                $enseignant->create(
                    $_POST['title'],
                    $_POST['description'],
                    $_SESSION['user']['id']
                );
                $message = 'Cours ajouté avec succès';
                break;
            case 'update':
                $enseignant->update(
                    $_POST['id'],
                    $_POST['title'],
                    $_POST['description']
                );
                $message = 'Cours mis à jour avec succès';
                break;
            case 'delete':
                $enseignant->delete($_POST['id']);
                $message = 'Cours supprimé avec succès';
                break;
        }
    } catch (Exception $e) {
        $message = 'Erreur: ' . $e->getMessage();
    }
}

$courses = $enseignant->index();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Gérer les Cours</title>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php if (!empty($message)): ?>
        <div class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-emerald-500 text-white px-6 py-3 rounded-xl shadow-lg z-50" id="statusMessage">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    
    <div class="flex">
        <div class="w-64 h-screen bg-indigo-600 text-white">
            <div class="p-4">
                <h1 class="text-2xl font-bold">YouDemy Admin</h1>
                <ul class="mt-6">
                    
                    <li>
                        <a href="tags.php" class="block py-2 px-4 hover:bg-indigo-700">Manage Courses</a>
                    </li>
                    <li>
                        <a href="createCourses.php" class="block py-2 px-4 hover:bg-indigo-700">Create Courses</a>
                    </li>
                    <li>
                        <a href="index.php" class="block py-2 px-4 hover:bg-indigo-700">Manage Users</a>
                    </li>
                    <li>
                        <a href="login.php" class="block py-2 px-4 hover:bg-indigo-700">Logout</a>
                    </li>
                </ul>
            </div>
        </div>


    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-12">Gérer les Cours</h1>

        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <?php if($courses): foreach ($courses as $course): ?>
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    
                    <div class="p-6 bg-gradient-to-r from-emerald-500 to-emerald-600">
                        <div class="flex items-center gap-4">
                            <div class="bg-white/20 p-3 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white"><?php echo htmlspecialchars($course['title']); ?></h3>
                                <span class="text-emerald-100">Course #<?php echo htmlspecialchars($course['id']); ?></span>
                            </div>
                        </div>
                    </div>

                    
                    <div class="p-6">
                        <div class="bg-gray-50 rounded-xl p-4 mb-6">
                            <p class="text-gray-600">
                                <?php echo htmlspecialchars($course['description']); ?>
                            </p>
                        </div>

                        
                        <div class="flex gap-3">
                            <button 
                                onclick="editCourse('<?php echo $course['id']; ?>', '<?php echo htmlspecialchars($course['title']); ?>', '<?php echo htmlspecialchars($course['description']); ?>')"
                                class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-xl transition-colors flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Modifier
                            </button>
                            <form method="post" class="flex-1">
                                <input type="hidden" name="id" value="<?php echo $course['id']; ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?')"
                                    class="w-full bg-rose-500 hover:bg-rose-600 text-white px-4 py-2 rounded-xl transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; endif; ?>
        </div>

        
        <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">
                <?php echo isset($_POST['id']) ? 'Modifier le Cours' : 'Ajouter un Cours'; ?>
            </h2>
            <form id="courseForm" method="post" class="space-y-6">
                <input type="hidden" name="id" id="courseId">
                <input type="hidden" name="action" id="formAction" value="create">
                
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Titre du cours</label>
                    <input type="text" name="title" id="title" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition duration-300">
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" required rows="4"
                              class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition duration-300"></textarea>
                </div>
                
                <button type="submit" 
                    class="w-full bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-3 rounded-xl transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Enregistrer
                </button>
            </form>
        </div>
    </div>
    </div>
    </div>
    <script>
        window.onload = function() {
            var statusMessage = document.getElementById('statusMessage');
            if (statusMessage) {
                setTimeout(function() {
                    statusMessage.style.display = 'none';
                }, 5000);
            }
        }

        
        function editCourse(id, title, description) {
            document.getElementById('courseId').value = id;
            document.getElementById('title').value = title;
            document.getElementById('description').value = description;
            document.getElementById('formAction').value = 'update';
            window.scrollTo({
                top: document.getElementById('courseForm').offsetTop - 100,
                behavior: 'smooth'
            });
        }
    </script>
</body>
</html>