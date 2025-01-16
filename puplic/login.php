<?php


require_once '../app/controllers/session_manager.php';
function displayMessage($message, $type = 'error') {
    $class = ($type == 'error') ? 'bg-red-500' : 'bg-green-500';
    return "<div class='status-message {$class} text-white p-4 rounded-md text-center font-bold' id='statusMessageLogIn'>
        {$message}
    </div>";
}


$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    
    $errors = validateInput($email, $password);
    
    if (empty($errors)) {
        try {
            $db = App\Core\Database::getInstance();
            $sql = "SELECT * FROM users WHERE email = :email AND status = 'Active' LIMIT 1";
            $params = ['email' => $email];
            
            $result = $db->query($sql, $params);
            $user = $result[0] ?? null;
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_role'] = $user['role'];
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
                        $message = displayMessage("Invalid user role");
                        break;
                }
            } else {
                $message = displayMessage("Invalid email or password");
            }
        } catch (Exception $e) {
            $message = displayMessage("System error. Please try again later.");
            error_log("Login error: " . $e->getMessage());
        }
    } else {
        $message = displayMessage(implode('<br>', $errors));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - YouDemy</title>
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
        .login-container {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gradient-to-r from-blue-500 to-indigo-600 min-h-screen flex items-center justify-center p-4">
    <?php echo $message; ?>

    <div class="login-container w-full max-w-md bg-white rounded-lg shadow-2xl p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">YouDemy</h1>
            <p class="text-gray-600 mt-2">Welcome back! Please login to continue.</p>
        </div>

        <form method="POST" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="mt-1 block w-full px-4 py-3 rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    placeholder="Enter your email"
                    required
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="mt-1 block w-full px-4 py-3 rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    placeholder="Enter your password"
                    required
                >
            </div>

            <button 
                type="submit" 
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out"
            >
                Sign In
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Don't have an account? 
                <a href="register.php" class="font-medium text-indigo-600 hover:text-indigo-500 transition duration-150 ease-in-out">
                    Register here
                </a>
            </p>
        </div>
    </div>

    <script>
        window.onload = function() {
            var statusMessage = document.getElementById('statusMessageLogIn');
            if (statusMessage) {
                setTimeout(function() {
                    statusMessage.style.opacity = '0';
                    statusMessage.style.transition = 'opacity 0.5s ease-in-out';
                    setTimeout(function() {
                        statusMessage.style.display = 'none';
                    }, 500);
                }, 3000);
            }
        }
    </script>
</body>
</html>
