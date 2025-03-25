<?php
// Include database connection
include '../includes/connection.php';

// Get the OWNER_ID from the URL
$owner_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($owner_id) {
    // Query to fetch owner details
    $query = "SELECT * FROM owners WHERE OWNER_ID = $owner_id";
    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) > 0) {
        $owner = mysqli_fetch_assoc($result);
    } else {
        echo "<p>Owner not found.</p>";
        exit;
    }
} else {
    echo "<p>No owner ID provided.</p>";
    exit;
}

// Function to determine the file type
function getFileType($file)
{
    $file_extension = pathinfo($file, PATHINFO_EXTENSION);
    return strtolower($file_extension);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Approval</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<!-- Sidebar inclusion -->
<?php include '../includes/sidebar.php'; ?>

<!-- Content Area -->
<h1 class="text-2xl font-bold mb-5">Review Owner Approval</h1>

<div class="bg-white p-5 shadow-md rounded-lg">
    <h2 class="text-xl font-semibold mb-4">Owner Details</h2>

    <!-- Owner Details Section -->
    <div class="space-y-3">
        <p><strong>Full Name:</strong> <?= $owner['FIRST_NAME'] . " " . $owner['LAST_NAME'] ?></p>
        <p><strong>Gender:</strong> <?= $owner['GENDER'] ?></p>
        <p><strong>Email:</strong> <?= $owner['EMAIL'] ?></p>
        <p><strong>Phone:</strong> <?= $owner['PHONE_NUMBER'] ?></p>
        <p><strong>Date Submitted:</strong> <?= $owner['HIRED_DATE'] ?></p>
    </div>

    <div class="mt-6">
        <h3 class="text-lg font-semibold mb-4">Documents</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-3">
                <p><strong>Supporting Document 1:</strong></p>
                <?php
                $doc1 = $owner['DOCUMENT_1'];
                $file_type1 = getFileType($doc1);

                if ($file_type1 === 'jpg' || $file_type1 === 'jpeg' || $file_type1 === 'png' || $file_type1 === 'gif') {
                    // Display Image
                    echo "<img src='$doc1' alt='Document 1' class='w-full rounded-lg shadow-lg' />";
                } elseif ($file_type1 === 'pdf') {
                    // Display PDF
                    echo "<iframe src='$doc1' class='w-full h-60 border-2 rounded-lg' frameborder='0'></iframe>";
                } else {
                    echo "<a href='$doc1' target='_blank' class='text-blue-500'>View Document</a>";
                }
                ?>
            </div>

            <div class="space-y-3">
                <p><strong>Document 2:</strong></p>
                <?php
                $doc2 = $owner['DOCUMENT_2'];
                $file_type2 = getFileType($doc2);

                if ($file_type2 === 'jpg' || $file_type2 === 'jpeg' || $file_type2 === 'png' || $file_type2 === 'gif') {
                    // Display Image
                    echo "<img src='$doc2' alt='Document 2' class='w-full rounded-lg shadow-lg' />";
                } elseif ($file_type2 === 'pdf') {
                    // Display PDF
                    echo "<iframe src='$doc2' class='w-full h-60 border-2 rounded-lg' frameborder='0'></iframe>";
                } else {
                    echo "<a href='$doc2' target='_blank' class='text-blue-500'>View Document</a>";
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Approve/Reject buttons -->
    <div class="mt-5 flex justify-end space-x-4">
        <form action="approvals_approve.php" method="POST" class="inline-block">
            <input type="hidden" name="owner_id" value="<?= $owner['OWNER_ID'] ?>">
            <button type="submit" name="action" value="approve"
                class="bg-green-500 text-white px-4 py-2 rounded inline-flex items-center">
                <i class="fas fa-check mr-2"></i>Approve
            </button>
        </form>

        <form action="approvals_reject.php" method="POST" class="inline-block">
            <input type="hidden" name="owner_id" value="<?= $owner['OWNER_ID'] ?>">
            <button type="submit" name="action" value="reject"
                class="bg-red-500 text-white px-4 py-2 rounded inline-flex items-center">
                <i class="fas fa-times mr-2"></i>Reject
            </button>
        </form>

    </div>
</div>

</body>

</html>