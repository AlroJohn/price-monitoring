<?php
include '../includes/connection.php';

$category = 'Meatshop'; // Change this for other store categories

$query = "SELECT * FROM stores 
          INNER JOIN store_type ON stores.STORE_ID = store_type.STORE_ID 
          WHERE store_type.CATEGORY = '$category' 
          AND stores.SHOP_ID != 1";

$result = mysqli_query($db, $query);

include('../includes/sidebar.php'); // Sidebar for navigation
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $category ?> Stores</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4 text-center"><?= $category ?> Stores</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="bg-white shadow-md rounded-lg p-4">
                    <img src="<?= !empty($row['IMAGE']) && file_exists('../assets/store_img/' . $row['IMAGE']) ? '../assets/store_img/' . $row['IMAGE'] : '../assets/store_img/no_img.jpg' ?>"
                        class="w-full h-40 object-cover rounded-md">
                    <h2 class="text-lg font-semibold mt-2"><?= $row['SHOP_NAME'] ?></h2>
                    <p class="text-gray-600"><?= $row['ADDRESS'] ?></p>
                    <p class="text-sm text-green-500"><?= $row['AVAILABILITY'] == 'Open' ? 'Open Now' : 'Closed' ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>

</html>