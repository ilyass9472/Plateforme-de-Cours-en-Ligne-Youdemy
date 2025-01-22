<?php
// controllers/CourseController.php
namespace App\Controllers;

use App\Models\Course;
use App\Core\Auth;

class CourseController {
    private $courseModel;
    private $auth;

    public function __construct() {
        $this->courseModel = new Course();
        $this->auth = new Auth();
    }

    public function index() {
        $this->auth->requireRole('Enseignant');
        
        $courses = $this->courseModel->getAllCourses();
        $this->render('courses/index', ['courses' => $courses]);
    }

    public function create() {
        $this->auth->requireRole('Enseignant');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCourseData();
            
            $courseData = [
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'instructor_id' => $_SESSION['user']['id']
            ];

            $this->courseModel->createCourse(
                $courseData['title'], 
                $courseData['description'], 
                $courseData['instructor_id']
            );

            $this->redirect('/courses');
        }
    }

    public function update($id) {
        $this->auth->requireRole('Enseignant');
        
        $course = $this->courseModel->getCourseById($id);
        if (!$course || $course['instructor_id'] !== $_SESSION['user']['id']) {
            $this->forbidden();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCourseData();
            
            $courseData = [
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'instructor_id' => $_SESSION['user']['id']
            ];

            $this->courseModel->updateCourse(
                $id,
                $courseData['title'],
                $courseData['description'],
                $courseData['instructor_id']
            );

            $this->redirect('/courses');
        }
    }

    public function delete($id) {
        $this->auth->requireRole('Enseignant');
        
        $course = $this->courseModel->getCourseById($id);
        if (!$course || $course['instructor_id'] !== $_SESSION['user']['id']) {
            $this->forbidden();
        }

        $this->courseModel->deleteCourse($id);
        $this->redirect('/courses');
    }

    private function validateCourseData() {
        $errors = [];

        if (empty($_POST['title'])) {
            $errors[] = "Le titre est requis";
        }
        if (empty($_POST['description'])) {
            $errors[] = "La description est requise";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $this->redirect('/courses/create');
        }
    }

    private function render($view, $data = []) {
        extract($data);
        require_once __DIR__ . "/../views/{$view}.php";
    }

    private function redirect($path) {
        header("Location: {$path}");
        exit();
    }

    private function forbidden() {
        http_response_code(403);
        die('Accès interdit');
    }
}

// views/courses/index.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Gestion des cours</title>
</head>
<body class="bg-gray-900 text-white">
    <div class="container mx-auto p-4">
        <?php if (isset($_SESSION['errors'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
                <?php unset($_SESSION['errors']); ?>
            </div>
        <?php endif; ?>

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Gestion des cours</h1>
            <a href="/courses/create" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Nouveau cours
            </a>
        </div>
        
        <div class="bg-gray-800 rounded-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">Titre</th>
                        <th class="px-4 py-2 text-left">Description</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                    <tr class="border-t border-gray-700">
                        <td class="px-4 py-2"><?php echo htmlspecialchars($course['title']); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($course['description']); ?></td>
                        <td class="px-4 py-2 text-right">
                            <div class="flex justify-end space-x-2">
                                <a 
                                    href="/courses/edit/<?php echo $course['id']; ?>" 
                                    class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600"
                                >
                                    Modifier
                                </a>
                                <form 
                                    method="POST" 
                                    action="/courses/delete/<?php echo $course['id']; ?>" 
                                    class="inline"
                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?')"
                                >
                                    <button 
                                        type="submit" 
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
                                    >
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>