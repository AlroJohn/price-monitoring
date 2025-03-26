<?php
// Include database connection
include '../../includes/connection.php';
session_start();

$shop_id = $_SESSION['SHOP_ID'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];

// Check if location already exists
$query = "SELECT * FROM store_locations WHERE SHOP_ID = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $shop_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    // Update existing location
    $query_update = "UPDATE store_locations SET LATITUDE = ?, LONGITUDE = ? WHERE SHOP_ID = ?";
    $stmt_update = mysqli_prepare($db, $query_update);
    mysqli_stmt_bind_param($stmt_update, "ddi", $latitude, $longitude, $shop_id);
    mysqli_stmt_execute($stmt_update);
    $_SESSION['message'] = "Location updated successfully!";
} else {
    // Insert new location
    $query_insert = "INSERT INTO store_locations (SHOP_ID, LATITUDE, LONGITUDE) VALUES (?, ?, ?)";
    $stmt_insert = mysqli_prepare($db, $query_insert);
    mysqli_stmt_bind_param($stmt_insert, "idd", $shop_id, $latitude, $longitude);
    mysqli_stmt_execute($stmt_insert);
    $_SESSION['message'] = "Location saved successfully!";
}

// Redirect to map.php
header("Location: map.php");
exit();
?>