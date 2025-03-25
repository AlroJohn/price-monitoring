<?php
// Include database connection
include '../includes/connection.php';

// Query to fetch pending approvals
$query = "SELECT * FROM owners WHERE STATUS = 'Pending'";
$result = mysqli_query($db, $query);

// Check for success notification
$status = isset($_GET['status']) ? $_GET['status'] : '';
$notification_message = '';
$notification_class = '';

if ($status === 'approved') {
    $notification_message = 'Owner has been successfully approved!';
    $notification_class = 'bg-green-500 text-white';
} elseif ($status === 'rejected') {
    $notification_message = 'Owner has been successfully rejected!';
    $notification_class = 'bg-red-500 text-white';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approvals</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<!-- Sidebar inclusion -->
<?php include '../includes/sidebar.php'; ?>

<!-- Content Area -->
<h1 class="text-2xl font-bold mb-5">Pending Approvals</h1>

<!-- Notification Section -->
<?php if ($notification_message): ?>
    <div class="p-4 mb-4 rounded <?= $notification_class ?> text-center">
        <?= $notification_message ?>
    </div>
<?php endif; ?>

<table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
    <thead class="bg-gray-800 text-white text-left">
        <tr>
            <th class="p-3">Owner ID</th>
            <th class="p-3">Name</th>
            <th class="p-3">Email</th>
            <th class="p-3">Phone</th>
            <th class="p-3">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr class="border-b text-left">
                    <td class="p-3"><?= $row['OWNER_ID'] ?></td>
                    <td class="p-3"><?= $row['FIRST_NAME'] . " " . $row['LAST_NAME'] ?></td>
                    <td class="p-3"><?= $row['EMAIL'] ?></td>
                    <td class="p-3"><?= $row['PHONE_NUMBER'] ?></td>
                    <td class="p-3">
                        <!-- Changed to "Review" button -->
                        <a href="approvals_review.php?id=<?= $row['OWNER_ID'] ?>"
                            class="bg-yellow-500 text-white px-3 py-1 rounded inline-flex items-center">
                            <i class="fas fa-search mr-2"></i>Review
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="p-3 text-red-500 text-lg font-semibold text-center">No Pending Approvals!</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>

</html>