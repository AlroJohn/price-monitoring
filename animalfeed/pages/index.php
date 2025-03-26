<?php
// Start the session to access session variables
session_start();

// Include database connection
include '../../includes/connection.php';

// Check if the session variable for OWNER_ID is set
if (!isset($_SESSION['OWNER_ID'])) {
    // Redirect to login page if the session is not set
    header('Location: login.php');
    exit();
}

// Fetch store owner details from session
$owner_id = $_SESSION['OWNER_ID'];  // Get OWNER_ID from session

// Fetch SHOP_ID using OWNER_ID
$query = "SELECT SHOP_ID FROM owners WHERE OWNER_ID = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $owner_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
$shop_id = $row['SHOP_ID'];  // Get the SHOP_ID associated with the logged-in owner

// Fetch SHOP_NAME from the store's details using SHOP_ID
$query_shop_name = "SELECT SHOP_NAME FROM stores WHERE SHOP_ID = ?";
$stmt_shop_name = mysqli_prepare($db, $query_shop_name);
mysqli_stmt_bind_param($stmt_shop_name, "i", $shop_id);
mysqli_stmt_execute($stmt_shop_name);
$result_shop_name = mysqli_stmt_get_result($stmt_shop_name);
$shop_name_row = mysqli_fetch_assoc($result_shop_name);
$shop_name = $shop_name_row['SHOP_NAME'];  // Get the SHOP_NAME as a string

// Fetch products for the store owner
$query_products = "SELECT * FROM product WHERE SHOP_ID = ? AND pro_status = 'active'";
$stmt_products = mysqli_prepare($db, $query_products);
mysqli_stmt_bind_param($stmt_products, "i", $shop_id);
mysqli_stmt_execute($stmt_products);
$result_products = mysqli_stmt_get_result($stmt_products);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Store Owner Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        /* Background image styling */
        body {
            background-image: url('../../img/animal_bg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white;
        }

        /* Custom styles for the table */
        table {
            border-collapse: collapse;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            overflow: hidden;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 10px;
            text-align: left;
            font-size: 1rem;
        }

        th {
            background-color: #6b8e23;
            /* Olive green for agriculture feel */
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #fdf6e3;
            /* Light beige */
        }

        tr:hover {
            background-color: #f5deb3;
            /* Wheat color hover effect */
        }

        /* Header section styling */
        .header {
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border-radius: 10px;
            padding: 30px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .header h1 {
            font-size: 3rem;
            font-weight: 700;
            color: #daa520;
            /* Goldenrod for a warm farm feel */
        }

        .header p {
            font-size: 1.125rem;
            margin-top: 10px;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Dashboard Cards */
        .card {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .card i {
            font-size: 2.5rem;
            color: #8b4513;
            /* SaddleBrown for farm-related icons */
        }

        .card h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
        }

        .card p {
            color: #6b7280;
        }

        /* Grid Layout for Dashboard Cards */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        /* Footer styling */
        footer {
            text-align: center;
            padding: 10px;
            color: #fff;
            background-color: #333;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>

<body>
    <!-- Include Top Bar -->
    <?php include '../include/topbar.php'; ?>

    <!-- Dashboard Container -->
    <div class="max-w-7xl mx-auto px-4 py-6">

        <!-- Dashboard Cards -->
        <div class="grid mt-6">
            <!-- Manage Products Card -->
            <a href="products.php" class="card">
                <i class="ph ph-package text-green-700"></i>
                <div>
                    <h2>Manage Products</h2>
                    <p>Add, edit, or remove products</p>
                </div>
            </a>

            <!-- Reservations Card -->
            <a href="reservations.php" class="card">
                <i class="ph ph-list-checks text-yellow-600"></i>
                <div>
                    <h2>Reservations</h2>
                    <p>Approve or reject customer requests</p>
                </div>
            </a>

            <!-- Store Settings Card -->
            <a href="settings.php" class="card">
                <i class="ph ph-gear text-brown-700"></i>
                <div>
                    <h2>Store Settings</h2>
                    <p>Customize your store settings</p>
                </div>
            </a>
        </div>

        <!-- Product List Table -->
        <div class="mt-6 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold text-green-800">Trending Products</h2>
            <table class="min-w-full mt-4">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Product Code</th>
                        <th class="px-4 py-2">Product Name</th>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2">Price</th>
                        <th class="px-4 py-2">QTY</th>
                        <th class="px-4 py-2">View / Click</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800">
                    <?php if (mysqli_num_rows($result_products) > 0): ?>
                        <?php while ($product = mysqli_fetch_assoc($result_products)) { ?>
                            <tr>
                                <td class="px-4 py-2"><?= $product['PRODUCT_CODE'] ?></td>
                                <td class="px-4 py-2"><?= $product['NAME'] ?></td>
                                <td class="px-4 py-2"><?= $product['DESCRIPTION'] ?></td>
                                <td class="px-4 py-2"><?= $product['PRICE'] ?></td>
                                <td class="px-4 py-2"><?= $product['QTY_STOCK'] ?></td>
                                <td class="px-4 py-2">0</td> <!-- Placeholder for click count -->
                            </tr>
                        <?php } ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-4 py-2 text-center text-red-600">No Product Available!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>