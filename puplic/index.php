<?php
session_start();
require_once '../autoload.php';
require_once '../core/Database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'Admin') {
    header('Location: login.php');
    exit();
}

$db = App\Core\Database::getInstance();
$sql = "SELECT * FROM users";
$users = $db->query($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $new_status = $_POST['status'];

    $update_sql = "UPDATE users SET status = :status WHERE id = :user_id";
    $params = [
        'status' => $new_status,
        'user_id' => $user_id
    ];

    try {
        $db->query($update_sql, $params);
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        echo "Erreur lors de la mise à jour du statut: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - YouDemy</title>
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
<body class="bg-gray-100 font-sans">

<div class="status-message bg-green-500 text-white p-4 rounded-md text-center font-bold" id ='statusMessage'>
            bounjour Mr ilyass
        </div>

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

        
        <div class="flex-1 p-6">
            <div class="flex justify-between items-center">
                <h2 class="text-3xl font-semibold text-gray-800">Admin Dashboard</h2>
            </div>

            <div class="mt-8">

                <div class="bg-white p-6 rounded-lg shadow-md mt-6">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">Users List</h3>
                    <table class="min-w-full table-auto">
                        <thead class="bg-indigo-600 text-white">
                            <tr>
                                <th class="py-3 px-4 text-left">ID</th>
                                <th class="py-3 px-4 text-left">Name</th>
                                <th class="py-3 px-4 text-left">Email</th>
                                <th class="py-3 px-4 text-left">Role</th>
                                <th class="py-3 px-4 text-left">Status</th>
                                <th class="py-3 px-4 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr class="border-b">
                                    <td class="py-3 px-4"><?php echo htmlspecialchars($user['id']); ?></td>
                                    <td class="py-3 px-4"><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td class="py-3 px-4"><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td class="py-3 px-4"><?php echo htmlspecialchars($user['role']); ?></td>
                                    <td class="py-3 px-4"><?php echo htmlspecialchars($user['status']); ?></td>
                                    <td class="py-3 px-4">
                                        
                                        <form method="POST" action="index.php" class="inline-block">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <select name="status" class="bg-indigo-600 text-white py-2 px-4 rounded-md">
                                                <option value="Active" <?php echo $user['status'] === 'Active' ? 'selected' : ''; ?>>Active</option>
                                                <option value="Suspended" <?php echo $user['status'] === 'Suspended' ? 'selected' : ''; ?>>Suspended</option>
                                                <option value="Non Active" <?php echo $user['status'] === 'Non Active' ? 'selected' : ''; ?>>Non Active</option>
                                            </select>
                                            <button type="submit" class="ml-2 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Update Status</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
