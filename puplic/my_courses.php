<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Apprenant') {
    header('Location: login.php');
    exit();
}

require_once '../app/models/Course.php';
$courseModel = new App\Models\Course();


$sql = "SELECT * FROM users WHERE email = :email AND status = 'active' LIMIT 1";


$courses = $courseModel->getUserCourses($_SESSION['user']['id']);


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Mes cours</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-8">Mes cours</h1>

        <?php if (empty($courses)): ?>
            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                <p class="text-gray-600">Vous n'êtes inscrit à aucun cours pour le moment.</p>
                <a href="catalog.php" class="inline-block mt-4 bg-blue-500 text-white px-6 py-2 rounded">
                    Parcourir le catalogue
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($courses as $course): ?>
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2">
                                <?php echo htmlspecialchars($course['title']); ?>
                            </h3>
                            <p class="text-gray-600 mb-4">
                                <?php echo htmlspecialchars($course['description']); ?>
                            </p>
                            <div class="mb-4">
                                <span class="text-sm text-gray-500">
                                    Enseignant: <?php echo htmlspecialchars($course['instructor_name']); ?>
                                </span>
                            </div>
                            
                            <div class="mt-4">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div 
                                        class="bg-blue-500 h-2.5 rounded-full" 
                                        style="width: <?php echo $course['progress'] ?? 0; ?>%"
                                    ></div>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">
                                    Progression : <?php echo $course['progress'] ?? 0; ?>%
                                </p>
                            </div>
                            <a 
                                href="course_content.php?id=<?php echo $course['id']; ?>" 
                                class="block mt-4 text-center bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition-colors"
                            >
                                Continuer le cours
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>