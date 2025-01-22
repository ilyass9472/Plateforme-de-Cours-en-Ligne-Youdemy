<?php
require_once '../core/Database.php';

$errors = [];
$success = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    
    if (empty($name)) {
        $errors[] = "Le nom est requis.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Une adresse e-mail valide est requise.";
    }
    if (empty($password) || strlen($password) < 6) {
        $errors[] = "Le mot de passe doit comporter au moins 6 caractères.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $db = App\Core\Database::getInstance();
        $sql = "INSERT INTO users (name, email, password, role, status, created_at) 
                VALUES (:name, :email, :password, 'user', 'Active', NOW())";
        $params = [
            'name' => $name,
            'email' => $email,
            'password' => $hashed_password
        ];

        try {
            $db->query($sql, $params);
            $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        } catch (Exception $e) {
            $errors[] = "Erreur lors de l'inscription : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - YouDemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex justify-center items-center min-h-screen">
        <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold text-gray-800 text-center">Inscription</h2>
            
            
            <?php if (!empty($errors)): ?>
                <div class="bg-red-100 text-red-800 p-4 rounded mt-4">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="bg-green-100 text-green-800 p-4 rounded mt-4">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            
            <form method="POST" action="register.php" class="mt-6">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Nom complet</label>
                    <input type="text" name="name" id="name" class="w-full p-2 border rounded focus:outline-none focus:ring focus:ring-indigo-300" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Adresse e-mail</label>
                    <input type="email" name="email" id="email" class="w-full p-2 border rounded focus:outline-none focus:ring focus:ring-indigo-300" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Mot de passe</label>
                    <input type="password" name="password" id="password" class="w-full p-2 border rounded focus:outline-none focus:ring focus:ring-indigo-300" required>
                </div>
                <div class="mb-4">
                    <label for="confirm_password" class="block text-gray-700">Confirmer le mot de passe</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="w-full p-2 border rounded focus:outline-none focus:ring focus:ring-indigo-300" required>
                </div>
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">
                    S'inscrire
                </button>
            </form>
            <p class="text-gray-600 text-sm text-center mt-4">
                Vous avez déjà un compte ? <a href="login.php" class="text-indigo-600 hover:underline">Connectez-vous</a>.
            </p>
        </div>
    </div>
</body>
</html>
