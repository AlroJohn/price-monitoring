<?php
include '../includes/connection.php';
include '../includes/sidebar.php';

// Modified query to select only stores with 'Active' status
$query = "SELECT s.*, o.STATUS, l.PUROK, l.BARANGAY 
          FROM stores s
          LEFT JOIN owners o ON s.SHOP_ID = o.SHOP_ID
          LEFT JOIN location l ON o.LOCATION_ID = l.LOCATION_ID
          WHERE o.STATUS = 'Active'"; // Filter for 'Active' status

$result = mysqli_query($db, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stores</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <h1 class="text-2xl font-bold mb-5">Stores List</h1>

    <div class="overflow-x-auto">
        <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="text-left p-3">Store ID</th>
                    <th class="text-left p-3">Shop Name</th>
                    <th class="text-left p-3">Address</th>
                    <th class="text-left p-3">Location</th>
                    <th class="text-left p-3">Status</th>
                    <th class="text-left p-3">Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <?php
                        $location = trim($row['PUROK'] . ', ' . $row['BARANGAY'] . ', Daraga, Albay');
                        ?>
                        <tr class="border-b">
                            <td class="p-3"><?= $row['STORE_ID'] ?></td>
                            <td class="p-3"><?= $row['SHOP_NAME'] ?></td>
                            <td class="p-3"><?= $row['ADDRESS'] ?></td>
                            <td class="p-3"><?= $location ?></td>
                            <td class="p-3"><?= $row['STATUS'] ?? 'Unknown' ?></td>
                            <td class="p-3"><?= $row['TIMESTAMP'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="p-3 text-left">No Active Stores Registered!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>

</html>