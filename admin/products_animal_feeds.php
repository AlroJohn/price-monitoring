<?php
include '../includes/connection.php';

$category = 'Animal Feeds'; // Change this for other product categories

// Fetch distinct stores for the selected category (excluding SHOP_ID = 1)
$storeQuery = "SELECT DISTINCT stores.SHOP_ID, stores.SHOP_NAME 
               FROM stores 
               INNER JOIN store_type ON stores.STORE_ID = store_type.STORE_ID 
               WHERE store_type.CATEGORY = '$category' 
               AND stores.SHOP_ID != 1";

$storeResult = mysqli_query($db, $storeQuery);

include('../includes/sidebar.php'); // Sidebar for navigation
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $category ?> Products</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6 text-center"><?= $category ?> Products</h1>

        <?php while ($store = mysqli_fetch_assoc($storeResult)): ?>
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-3"><?= $store['SHOP_NAME'] ?></h2>

                <?php
                // Fetch products for this store
                $productQuery = "SELECT * FROM product 
                                WHERE SHOP_ID = {$store['SHOP_ID']}";
                $productResult = mysqli_query($db, $productQuery);
                ?>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    <?php while ($product = mysqli_fetch_assoc($productResult)): ?>
                        <div class="bg-white shadow-md rounded-lg p-4">
                            <img src="<?= !empty($product['IMAGE']) && file_exists('../assets/product_img/' . $product['IMAGE']) ? '../assets/product_img/' . $product['IMAGE'] : '../assets/product_img/no_img.jpg' ?>"
                                class="w-full h-40 object-cover rounded-md">
                            <h3 class="text-lg font-semibold mt-2"><?= $product['NAME'] ?></h3>
                            <p class="text-sm text-gray-600"><?= $product['DESCRIPTION'] ?></p>
                            <p class="text-sm font-bold text-blue-500">₱<?= number_format($product['PRICE'], 2) ?></p>
                            <p class="text-sm text-green-500"><?= $product['QTY_STOCK'] > 0 ? 'In Stock' : 'Out of Stock' ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>

</html>