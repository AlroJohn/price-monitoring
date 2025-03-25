<?php
// Include database connection
include '../includes/connection.php';

// Check if the owner_id is passed in the request
if (isset($_POST['owner_id'])) {
    $owner_id = $_POST['owner_id'];

    // Query to update the status to 'Approved'
    $query = "UPDATE owners SET STATUS = 'Active' WHERE OWNER_ID = $owner_id";

    if (mysqli_query($db, $query)) {
        // Redirect back to the approval page or another page as necessary
        header("Location: approvals.php?status=approved");
        exit();
    } else {
        echo "Error updating status: " . mysqli_error($db);
    }
} else {
    echo "Owner ID is missing.";
}
?>