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
    <title>Animal Feed Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>

<body class="bg-gray-100">

    <!-- Navigation Bar -->
    <nav class="bg-green-700 shadow-md py-3 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between text-white">
            <!-- Left Side (Logo or Shop Name) -->
            <div class="w-1/3">
                <span class="text-xl font-semibold"><?= htmlspecialchars($shop_name) ?> </span>
            </div>

            <!-- Center (Empty for future use) -->
            <div class="w-1/3"></div>

            <!-- Right Side (Navigation Links) -->
            <div class="w-1/3 flex justify-end space-x-4">
                <a href="index.php"
                    class="flex items-center px-4 py-2 rounded-lg transition hover:bg-green-800 <?= ($current_page == 'index.php') ? 'bg-green-800 font-bold' : '' ?>">
                    <i class="ph ph-house text-lg"></i> <span class="ml-2">Home</span>
                </a>
                <a href="products.php"
                    class="flex items-center px-4 py-2 rounded-lg transition hover:bg-green-800 <?= ($current_page == 'products.php') ? 'bg-green-800 font-bold' : '' ?>">
                    <i class="ph ph-package text-lg"></i> <span class="ml-2">Products</span>
                </a>
                <a href="reservations.php"
                    class="flex items-center px-4 py-2 rounded-lg transition hover:bg-green-800 <?= ($current_page == 'reservations.php') ? 'bg-green-800 font-bold' : '' ?>">
                    <i class="ph ph-list-checks text-lg"></i> <span class="ml-2">Reservations</span>
                </a>
                <a href="map.php"
                    class="flex items-center px-4 py-2 rounded-lg transition hover:bg-green-800 <?= ($current_page == 'map.php') ? 'bg-green-800 font-bold' : '' ?>">
                    <i class="ph ph-calendar text-lg"></i> <span class="ml-2">Business </span>
                </a>

                <a href="settings.php"
                    class="flex items-center px-4 py-2 rounded-lg transition hover:bg-green-800 <?= ($current_page == 'settings.php') ? 'bg-green-800 font-bold' : '' ?>">
                    <i class="ph ph-gear text-lg"></i> <span class="ml-2">Settings</span>
                </a>
                <a href="logout.php" class="flex items-center px-4 py-2 rounded-lg transition hover:bg-red-500">
                    <i class="ph ph-sign-out text-lg"></i> <span class="ml-2">Logout</span>
                </a>
            </div>

        </div>
    </nav>

    <!-- Animal Feed Product Section -->

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php
        // Fetch Animal Feed Products from Database
        $query = "SELECT PRODUCT_CODE, NAME, DESCRIPTION, PRICE, IMAGE FROM product WHERE SHOP_ID = ? AND CATEGORY_ID = (SELECT CATEGORY_ID FROM store_type WHERE STORE_ID = ? AND CATEGORY = 'Animal Feed')";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "ii", $shop_id, $shop_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)):
            $image_path = !empty($row['IMAGE']) && file_exists("../../assets/product_img/" . $row['IMAGE'])
                ? "../../assets/product_img/" . $row['IMAGE']
                : "../../assets/product_img/no_img.jpg";
            ?>
            <div class="bg-white shadow-lg rounded-lg p-4 transform hover:scale-105 transition">
                <img src="<?= $image_path ?>" alt="<?= htmlspecialchars($row['NAME']) ?>"
                    class="w-full h-40 object-cover rounded-md">
                <h3 class="text-lg font-bold mt-3"><?= htmlspecialchars($row['NAME']) ?></h3>
                <p class="text-sm text-gray-600"><?= htmlspecialchars($row['DESCRIPTION']) ?></p>
                <p class="text-green-600 font-bold mt-2">₱<?= number_format($row['PRICE'], 2) ?></p>
                <div class="mt-4 flex space-x-2">
                    <a href="details.php?code=<?= $row['PRODUCT_CODE'] ?>"
                        class="flex-1 text-center bg-blue-600 text-white px-3 py-2 rounded-md hover:bg-blue-700 transition">
                        Details
                    </a>
                    <a href="reserve.php?code=<?= $row['PRODUCT_CODE'] ?>"
                        class="flex-1 text-center bg-yellow-600 text-white px-3 py-2 rounded-md hover:bg-yellow-700 transition">
                        Reserve
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>