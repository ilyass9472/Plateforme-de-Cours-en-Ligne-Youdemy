<?php
session_start();
require_once '../app/controllers/session_manager.php';
require_once '../core/Database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Veuillez entrer un email valide.';
    } elseif (empty($password)) {
        $message = 'Veuillez entrer un mot de passe.';
    } else {
        try {
            $db = App\Core\Database::getInstance();
            $sql = "SELECT * FROM users WHERE email = :email AND status = 'Active' LIMIT 1";
            $params = ['email' => $email];

            $result = $db->query($sql, $params);
            $user = $result[0] ?? null;

            if ($user && $password == $user['password']) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'status' => $user['status']
                ];

                switch ($user['role']) {
                    case 'admin':
                        header("Location: index.php");
                        exit;
                    case 'Apprenant':
                        header("Location: courses.php");
                        exit;
                    case 'Enseignant':
                        header("Location: createCourses.php");
                        exit;
                    default:
                        $message = 'Rôle invalide.';
                }
            } else {
                $message = 'Email ou mot de passe incorrect.';
            }
        } catch (Exception $e) {
            $message = 'Erreur système, veuillez réessayer plus tard.';
            error_log("Erreur de connexion: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - YouDemy</title>
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
        }
    </style>
    <script>
        window.onload = function() {

            var statusMessage = document.getElementById('statusMessage');
            if (statusMessage) {
                setTimeout(function() {
                    statusMessage.style.display = 'none';
                }, 3000);
            }
        }
    </script>
</head>
<body class="bg-gradient-to-r from-blue-500 to-indigo-600 min-h-screen flex items-center justify-center p-4">
    <?php if ($message): ?>
        <div class="status-message bg-red-500 text-white p-4 rounded-md text-center font-bold" id ='statusMessage'>
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="w-full max-w-md bg-white rounded-lg shadow-2xl p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">YouDemy</h1>
            <p class="text-gray-600 mt-2">Bienvenue ! Veuillez vous connecter pour continuer.</p>
        </div>

        <form method="POST" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="mt-1 block w-full px-4 py-3 rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Entrez votre email" required>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                <input type="password" id="password" name="password" class="mt-1 block w-full px-4 py-3 rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Entrez votre mot de passe" required>
            </div>

            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                Se connecter
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Pas encore de compte ? 
                <a href="register.php" class="font-medium text-indigo-600 hover:text-indigo-500 transition duration-150 ease-in-out">
                    Inscrivez-vous ici
                </a>
            </p>
        </div>
    </div>
</body>
</html>
