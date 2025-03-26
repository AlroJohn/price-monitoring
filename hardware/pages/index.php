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
    <title>Hardware Store Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        /* Background Styling */
        body {
            background: url('../../img/hardware_bg.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }

        /* Header */
        .header {
            background: linear-gradient(145deg, #4b5563, #1f2937);
            padding: 30px 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: inset 2px 2px 5px #000, inset -2px -2px 5px #5a6b7e;
        }

        .header h1 {
            font-size: 3rem;
            font-weight: bold;
            text-transform: uppercase;
            color: #facc15;
            /* Metallic gold */
        }

        /* Cards */
        .card {
            background: linear-gradient(145deg, #6b7280, #374151);
            padding: 20px;
            border-radius: 10px;
            box-shadow: inset 2px 2px 5px #1f2937, inset -2px -2px 5px #9ca3af;
            transition: all 0.3s ease;
            color: white;
        }

        .card:hover {
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }

        .card i {
            font-size: 2.5rem;
            color: #facc15;
        }

        .card h2 {
            font-size: 1.25rem;
            font-weight: 700;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            background: linear-gradient(145deg, #4b5563, #1f2937);
            border-radius: 10px;
            overflow: hidden;
            color: white;
        }

        th {
            background: #374151;
            color: #facc15;
            font-weight: bold;
            text-transform: uppercase;
            padding: 12px;
        }

        td {
            border: 1px solid #6b7280;
            padding: 10px;
            text-align: left;
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 10px;
            background: #1f2937;
            color: #facc15;
            font-weight: bold;
            border-top: 2px solid #facc15;
        }
    </style>
</head>

<body>
    <!-- Include Top Bar -->
    <?php include '../include/topbar.php'; ?>

    <!-- Dashboard Container -->
    <div class="max-w-7xl mx-auto px-4 py-6">


        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <a href="products.php" class="card">
                <i class="ph ph-package"></i>
                <h2>Manage Products</h2>
                <p>Add, edit, or remove products</p>
            </a>

            <a href="reservations.php" class="card">
                <i class="ph ph-list-checks"></i>
                <h2>Reservations</h2>
                <p>Approve or reject customer requests</p>
            </a>

            <a href="settings.php" class="card">
                <i class="ph ph-gear"></i>
                <h2>Store Settings</h2>
                <p>Customize your store settings</p>
            </a>
        </div>

        <!-- Product List Table -->
        <div class="mt-6 bg-gray-800 p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold text-yellow-400">Trending Products</h2>
            <table class="mt-4">
                <thead>
                    <tr>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>QTY</th>
                        <th>View / Click</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result_products) > 0): ?>
                        <?php while ($product = mysqli_fetch_assoc($result_products)) { ?>
                            <tr>
                                <td><?= $product['PRODUCT_CODE'] ?></td>
                                <td><?= $product['NAME'] ?></td>
                                <td><?= $product['DESCRIPTION'] ?></td>
                                <td><?= $product['PRICE'] ?></td>
                                <td><?= $product['QTY_STOCK'] ?></td>
                                <td>0</td> <!-- Placeholder for click count -->
                            </tr>
                        <?php } ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-red-500">No Product Available!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        &copy; <?= date("Y") ?> Hardware Shop - All Rights Reserved
    </footer>
</body>

</html>