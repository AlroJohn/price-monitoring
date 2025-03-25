<?php
// Include database connection
include '../includes/connection.php';

// Query to fetch product data with active status and shop name
$query = "SELECT p.PRODUCT_ID, p.NAME, p.DESCRIPTION, p.PRICE, p.QTY_STOCK, p.SHOP_ID, s.SHOP_NAME 
          FROM product p
          JOIN stores s ON p.SHOP_ID = s.SHOP_ID
          WHERE p.pro_status = 'Active'";
$result = mysqli_query($db, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>


<!-- Sidebar inclusion -->
<?php include '../includes/sidebar.php'; ?>

<!-- Content Area -->
<h1 class="text-2xl font-bold mb-5">Products List</h1>

<table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
    <thead class="bg-gray-800 text-white text-left">
        <tr>
            <th class="p-3">Product ID</th>
            <th class="p-3">Name</th>
            <th class="p-3">Description</th>
            <th class="p-3">Price</th>
            <th class="p-3">Stock</th>
            <th class="p-3">Shop Name</th>
        </tr>
    </thead>
    <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr class="border-b text-left">
                    <td class="p-3"><?= $row['PRODUCT_ID'] ?></td>
                    <td class="p-3"><?= $row['NAME'] ?></td>
                    <td class="p-3"><?= $row['DESCRIPTION'] ?></td>
                    <td class="p-3">₱<?= number_format($row['PRICE'], 2) ?></td>
                    <td class="p-3"><?= $row['QTY_STOCK'] ?></td>
                    <td class="p-3"><?= $row['SHOP_NAME'] ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="p-3 text-red-500 text-lg font-semibold text-center">No Product Available!</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>

</html>