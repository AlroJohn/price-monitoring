<?php
// Start the session
session_start();
include('../../includes/connection.php');

// Check if the user is a store owner before closing the store
if (isset($_SESSION['SHOP_ID'])) {
    $shop_id = $_SESSION['SHOP_ID'];

    // Update the store availability to "Close"
    $updateQuery = "UPDATE stores SET AVAILABILITY = 'Close' WHERE SHOP_ID = ?";
    $stmt = mysqli_prepare($db, $updateQuery);
    mysqli_stmt_bind_param($stmt, "i", $shop_id);
    mysqli_stmt_execute($stmt);
}

// Destroy all session data
session_unset();
session_destroy();

// Redirect to the login page
header("Location: ../../admin/login-admin.php");
exit();
?>