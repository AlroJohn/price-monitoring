<?php
// Include database connection
include '../../includes/connection.php';

// Fetch store owner details using the SHOP_ID from the session
$shop_id = $_SESSION['SHOP_ID'];  // Get SHOP_ID from session

// Fetch SHOP_NAME from the stores table using SHOP_ID
$query_shop_name = "SELECT SHOP_NAME FROM stores WHERE SHOP_ID = ?";
$stmt_shop_name = mysqli_prepare($db, $query_shop_name);
mysqli_stmt_bind_param($stmt_shop_name, "i", $shop_id);
mysqli_stmt_execute($stmt_shop_name);
$result_shop_name = mysqli_stmt_get_result($stmt_shop_name);
$shop_name_row = mysqli_fetch_assoc($result_shop_name);
$shop_name = $shop_name_row['SHOP_NAME'];

// Get current page
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Store Owner Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>

<body class="bg-gray-100">
    <!-- Navigation Bar -->
    <nav class="bg-red-700 shadow-md py-3">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between text-white">
            <!-- Left Side (Logo or Shop Name) -->
            <div class="w-1/3">
                <span class="text-xl font-semibold"><?= htmlspecialchars($shop_name) ?> </span>
            </div>

            <!-- Center (Empty for future use) -->
            <div class="w-1/3"></div>

            <!-- Right Side (Navigation Links) -->
            <div class="w-1/3 flex justify-end space-x-6">
                <!-- Home Icon before Manage Products -->
                <a href="index.php" id="home-link" class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-all duration-200 hover:bg-red-800 hover:scale-105 
                    <?= ($current_page == 'index.php') ? 'bg-red-800 font-bold' : '' ?>">
                    <i class="ph ph-house text-lg"></i> <!-- Home Icon -->
                    <span>Home</span>
                </a>

                <a href="products.php" id="products-link" class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-all duration-200 hover:bg-red-800 hover:scale-105 
                    <?= ($current_page == 'products.php') ? 'bg-red-800 font-bold' : '' ?>">
                    <i class="ph ph-package text-lg"></i>
                    <span class="whitespace-nowrap">Manage Products</span>
                </a>

                <a href="reservations.php" id="reservations-link" class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-all duration-200 hover:bg-red-800 hover:scale-105 
                    <?= ($current_page == 'reservations.php') ? 'bg-red-800 font-bold' : '' ?>">
                    <i class="ph ph-list-checks text-lg"></i>
                    <span>Reservations</span>
                </a>

                <a href="map.php" id="business-link" class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-all duration-200 hover:bg-red-800 hover:scale-105
                    <?= ($current_page == 'map.php') ? 'bg-red-800 font-bold' : '' ?>">
                    <i class="ph ph-briefcase text-lg"></i>
                    <span>Business</span>
                </a>

                <a href="settings.php" id="settings-link" class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-all duration-200 hover:bg-red-800 hover:scale-105 
                    <?= ($current_page == 'settings.php') ? 'bg-red-800 font-bold' : '' ?>">
                    <i class="ph ph-gear text-lg"></i>
                    <span>Settings</span>
                </a>

                <a href="logout.php" id="logout-link"
                    class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-all duration-200 hover:bg-red-500 hover:scale-105">
                    <i class="ph ph-sign-out text-lg"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </nav>

</body>

</html>