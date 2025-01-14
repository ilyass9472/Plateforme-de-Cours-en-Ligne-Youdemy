<?php
include '../core/Database.php';



try {
    
    $db = Database::getInstance();
    $statusMessage = "<div class='status-message bg-green-500 text-white p-4 rounded-md text-center font-bold' id ='statusMessage'>
            Connection successful!.
          </div>";
} catch (Exception $e) {
    
    $statusMessage = "<div class='status-message bg-red-500 text-white p-4 rounded-md text-center font-bold' id ='statusMessage'>
            Connection failed: " . $e->getMessage() . "
          </div>";
}

$db = Database::getInstance();
$sql = "SELECT name, email, role, status FROM users";
$data = $db->query($sql);

function getStatusColor($status) {
    switch ($status) {
        case 'Active':
            return 'green-600'; 
        case 'Pending':
            return 'yellow-600'; 
        case 'Suspended':
            return 'red-600'; 
        default:
            return 'gray-600'; 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tailwind Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .status-message {
            position: fixed;
            top: 0;
            left: 50%;
            z-index: 1000;
            width: auto;
            padding: 1rem;
            
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
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <?php echo $statusMessage; ?>

    <div class="flex flex-col md:flex-row">
        <div class="bg-gradient-to-r from-blue-900 to-indigo-800 w-full md:w-64 h-screen shadow-lg">
            <div class="flex items-center justify-center text-white py-4">
                <h1 class="text-3xl font-semibold">Dashboard</h1>
            </div>
            <nav class="mt-10 space-y-2">
                <a class="block py-2 px-4 text-white hover:bg-blue-700 rounded-md" href="#">Dashboard</a>
                <a class="block py-2 px-4 text-white hover:bg-blue-700 rounded-md" href="#">Users</a>
                <a class="block py-2 px-4 text-white hover:bg-blue-700 rounded-md" href="#">Settings</a>
                <a class="block py-2 px-4 text-white hover:bg-blue-700 rounded-md" href="#">Reports</a>
            </nav>
        </div>

        <div class="flex-1 p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-4 rounded-lg shadow-xl hover:shadow-2xl transition duration-300">
                    <h2 class="text-gray-700 font-semibold text-xl">Users</h2>
                    <p class="text-3xl font-bold">1,234</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-xl hover:shadow-2xl transition duration-300">
                    <h2 class="text-gray-700 font-semibold text-xl">Sales</h2>
                    <p class="text-3xl font-bold">$12,345</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-xl hover:shadow-2xl transition duration-300">
                    <h2 class="text-gray-700 font-semibold text-xl">Performance</h2>
                    <p class="text-3xl font-bold">89%</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-xl hover:shadow-2xl transition duration-300">
                    <h2 class="text-gray-700 font-semibold text-xl">Support</h2>
                    <p class="text-3xl font-bold">123</p>
                </div>
            </div>

            <div class="mt-6 bg-white shadow-lg rounded-lg overflow-hidden">
                <table class="table-auto w-full">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Email</th>
                            <th class="px-4 py-2 text-left">Role</th>
                            <th class="px-4 py-2 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (!empty($data)) {
                            foreach ($data as $row) { 
                                echo "<tr class='hover:bg-gray-100'>";
                                echo "<td class='border px-4 py-2'>" . htmlspecialchars($row["name"]) . "</td>";
                                echo "<td class='border px-4 py-2'>" . htmlspecialchars($row["email"]) . "</td>";
                                echo "<td class='border px-4 py-2'>" . htmlspecialchars($row["role"]) . "</td>";
                                echo "<td class='border px-4 py-2 text-" . getStatusColor($row["status"]) . "'>" . htmlspecialchars($row["status"]) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center py-4'>No data available</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
