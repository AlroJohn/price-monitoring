<?php
session_start();
include '../../includes/connection.php';

$shop_id = $_SESSION['SHOP_ID'];
$time_open = $_POST['time_open'];
$time_close = $_POST['time_close'];

$query = "UPDATE stores SET TIME_OPEN = ?, TIME_CLOSE = ? WHERE SHOP_ID = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "ssi", $time_open, $time_close, $shop_id);
mysqli_stmt_execute($stmt);

$_SESSION['message'] = "Store hours updated successfully!";
header("Location: map.php");
exit();
?>