<?php
include '../../includes/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['status'], $_POST['shop_id'])) {
    $shop_id = $_POST['shop_id'];
    $status = ($_POST['status'] == 'Open') ? 'Open' : 'Close';

    $query = "UPDATE stores SET AVAILABILITY = ? WHERE SHOP_ID = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "si", $status, $shop_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "success";
    } else {
        echo "error";
    }
}
?>