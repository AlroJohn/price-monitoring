<?php
// Database connection
$db = mysqli_connect('localhost', 'root', '') or die('Unable to connect. Check your connection parameters.');
mysqli_select_db($db, 'monitoring') or die(mysqli_error($db));

// Query to fetch user data including SHOP_ID and OWNER_ID
$query = "SELECT users.ID, users.USERNAME, users.TYPE_ID, owners.FIRST_NAME, owners.LAST_NAME, users.SHOP_ID, users.OWNER_ID
          FROM users
          JOIN owners ON users.OWNER_ID = owners.OWNER_ID";
$result = mysqli_query($db, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>


<!-- Sidebar inclusion -->
<?php include '../includes/sidebar.php'; ?>

<!-- Content Area -->
<h1 class="text-2xl font-bold mb-5">Users List</h1>

<table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
    <thead class="bg-gray-800 text-white text-left">
        <tr>
            <th class="p-3">ID</th>
            <th class="p-3">Username</th>
            <th class="p-3">Full Name</th>
            <th class="p-3">Type</th>
            <th class="p-3">Shop ID</th>
            <th class="p-3">Owner ID</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr class="border-b text-left">
                <td class="p-3"><?= $row['ID'] ?></td>
                <td class="p-3"><?= $row['USERNAME'] ?></td>
                <td class="p-3"><?= $row['FIRST_NAME'] . " " . $row['LAST_NAME'] ?></td>
                <td class="p-3">
                    <?= ($row['TYPE_ID'] == 1) ? 'Admin' : (($row['TYPE_ID'] == 2) ? 'Customer' : 'Manager') ?>
                </td>
                <td class="p-3"><?= $row['SHOP_ID'] ?></td>
                <td class="p-3"><?= $row['OWNER_ID'] ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>

</html>