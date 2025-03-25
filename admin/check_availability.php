<?php
require('../includes/connection.php'); // Make sure this path is correct

// Check if the username or store name is provided
if (isset($_POST['username'])) {
    // Check if username exists
    $username = $db->real_escape_string($_POST['username']);
    $query = "SELECT * FROM users WHERE USERNAME = '$username'";
    $result = $db->query($query);
    if ($result->num_rows > 0) {
        echo "Username is unavailable";
    } else {
        echo "Username is available";
    }
}

if (isset($_POST['shop_name'])) {
    // Check if shop name exists
    $shop_name = $db->real_escape_string($_POST['shop_name']);
    $query = "SELECT * FROM stores WHERE SHOP_NAME = '$shop_name'";
    $result = $db->query($query);
    if ($result->num_rows > 0) {
        echo "Store name is unavailable";
    } else {
        echo "Store name is available";
    }
}
?>