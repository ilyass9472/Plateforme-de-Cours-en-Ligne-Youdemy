<?php
session_start();
require_once '../autoload.php';
require_once '../core/Database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

$db = App\Core\Database::getInstance();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $user_id = $_POST['user_id'];
    $delete_sql = "DELETE FROM users WHERE id = :user_id";
    
    try {
        $db->query($delete_sql, ['user_id' => $user_id]);
        header("Location: index.php?message=User deleted successfully");
        exit();
    } catch (Exception $e) {
        echo "Error deleting user: " . $e->getMessage();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $status = $_POST['status'];

    $update_sql = "UPDATE users SET name = :name, email = :email, role = :role, status = :status WHERE id = :user_id";
    $params = [
        'name' => $name,
        'email' => $email,
        'role' => $role,
        'status' => $status,
        'user_id' => $user_id
    ];

    try {
        $db->query($update_sql, $params);
        header("Location: index.php?message=User updated successfully");
        exit();
    } catch (Exception $e) {
        echo "Error updating user: " . $e->getMessage();
    }
}


$sql = "SELECT * FROM users";
$users = $db->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - YouDemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            width: 50%;
            border-radius: 8px;
        }
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
</head>
<body class="bg-gray-100 font-sans">

    <?php if (isset($_GET['message'])): ?>
    <div class="status-message bg-green-500 text-white p-4 rounded-md text-center font-bold" id="statusMessage">
        <?php echo htmlspecialchars($_GET['message']); ?>
    </div>
    <?php endif; ?>

    
    <div id="updateModal" class="modal">
        <div class="modal-content">
            <h2 class="text-2xl font-bold mb-4">Update User</h2>
            <form id="updateForm" method="POST" action="index.php">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="user_id" id="update_user_id">
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Name:</label>
                    <input type="text" name="name" id="update_name" class="w-full p-2 border rounded">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Email:</label>
                    <input type="email" name="email" id="update_email" class="w-full p-2 border rounded">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Role:</label>
                    <select name="role" id="update_role" class="w-full p-2 border rounded">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Status:</label>
                    <select name="status" id="update_status" class="w-full p-2 border rounded">
                        <option value="Active">Active</option>
                        <option value="Suspended">Suspended</option>
                        <option value="Non Active">Non Active</option>
                    </select>
                </div>
                
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </div>

    <div class="flex">
        <div class="w-64 h-screen bg-indigo-600 text-white">
            <div class="p-4">
                <h1 class="text-2xl font-bold">YouDemy Admin</h1>
                <ul class="mt-6">
                    <li><a href="tags.php" class="block py-2 px-4 hover:bg-indigo-700">Manage Courses</a></li>
                    <li><a href="createCourses.php" class="block py-2 px-4 hover:bg-indigo-700">Create Courses</a></li>
                    <li><a href="index.php" class="block py-2 px-4 hover:bg-indigo-700">Manage Users</a></li>
                    <li><a href="login.php" class="block py-2 px-4 hover:bg-indigo-700">Logout</a></li>
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
                                    <td class="py-3 px-4 flex gap-2">
                                        <button onclick="openUpdateModal(<?php echo htmlspecialchars(json_encode($user)); ?>)" 
                                                class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                                            Update
                                        </button>
                                        
                                        <form method="POST" action="index.php" class="inline-block" onsubmit="return confirmDelete()">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700">
                                                Delete
                                            </button>
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

    <script>
        window.onload = function() {
            var statusMessage = document.getElementById('statusMessage');
            if (statusMessage) {
                setTimeout(function() {
                    statusMessage.style.display = 'none';
                }, 3000);
            }
        }

        
        function openUpdateModal(user) {
            document.getElementById('updateModal').style.display = 'block';
            document.getElementById('update_user_id').value = user.id;
            document.getElementById('update_name').value = user.name;
            document.getElementById('update_email').value = user.email;
            document.getElementById('update_role').value = user.role;
            document.getElementById('update_status').value = user.status;
        }

        function closeModal() {
            document.getElementById('updateModal').style.display = 'none';
        }

        
        function confirmDelete() {
            return confirm('Are you sure you want to delete this user?');
        }

        
        window.onclick = function(event) {
            var modal = document.getElementById('updateModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>

</body>
</html>