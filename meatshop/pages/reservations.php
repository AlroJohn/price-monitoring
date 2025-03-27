<?php
session_start();
include '../../includes/connection.php';

// Include Telerivet PHP client
require_once '../../telerivet-php-client/telerivet.php';

// Telerivet credentials
$api_key = "iorNG_tQnRg6wi4gySKTK7ZHeqESikOr5JgM"; // Replace with your Telerivet API key
$project_id = "PJ59c301d36eac6193"; // Replace with your Telerivet project ID

// Fixed recipient number (for testing)
$fixed_recipient = "+639637271887";

// Ensure SHOP_ID session is set
if (!isset($_SESSION['SHOP_ID'])) {
    header('Location: login.php');
    exit();
}

$shop_id = $_SESSION['SHOP_ID'];

// Process status updates
if (isset($_GET['action'], $_GET['id'])) {
    $reservation_id = (int) $_GET['id'];
    $action = $_GET['action'];

    $status_map = [
        'approve' => 'Approved',
        'reject' => 'Rejected',
        'for_pickup' => 'For Pick Up',
        'picked_up' => 'Picked Up'
    ];

    if (isset($status_map[$action])) {
        $new_status = $status_map[$action];

        // If action is approve or for_pickup, get product details and send notification
        if ($action == 'approve' || $action == 'for_pickup') {
            // Fetch reservation and product details
            $res_query = "SELECT r.PRODUCT_CODE, r.QUANTITY, r.CUSTOMER_NAME, r.CONTACT_NUMBER, 
                           p.NAME AS PRODUCT_NAME, p.PRICE 
                           FROM reservations r
                           JOIN product p ON r.PRODUCT_CODE = p.PRODUCT_CODE AND r.SHOP_ID = p.SHOP_ID
                           WHERE r.RESERVATION_ID = ? AND r.SHOP_ID = ?";

            $stmt_res = mysqli_prepare($db, $res_query);
            mysqli_stmt_bind_param($stmt_res, "ii", $reservation_id, $shop_id);
            mysqli_stmt_execute($stmt_res);
            $res_result = mysqli_stmt_get_result($stmt_res);

            if ($res_row = mysqli_fetch_assoc($res_result)) {
                $product_code = $res_row['PRODUCT_CODE'];
                $quantity = $res_row['QUANTITY'];
                $customer_name = $res_row['CUSTOMER_NAME'];
                $contact_number = $res_row['CONTACT_NUMBER'] ?? $fixed_recipient; // Use fixed recipient if contact number is not available
                $product_name = $res_row['PRODUCT_NAME'];
                $price = $res_row['PRICE'];

                // Calculate total amount
                $total_amount = $quantity * $price;

                // If moving to "For Pick Up", update product stock
                if ($action == 'for_pickup') {
                    // Deduct quantity from product stock
                    $update_stock = "UPDATE product SET QTY_STOCK = QTY_STOCK - ? WHERE PRODUCT_CODE = ? AND SHOP_ID = ?";
                    $stmt_stock = mysqli_prepare($db, $update_stock);
                    mysqli_stmt_bind_param($stmt_stock, "isi", $quantity, $product_code, $shop_id);
                    mysqli_stmt_execute($stmt_stock);
                    mysqli_stmt_close($stmt_stock);

                    // Message for ready for pickup
                    $message = "Hello " . strtoupper($customer_name) . ", your order " . strtoupper($product_name) . " is READY FOR PICK UP! Total amount: PHP " . number_format($total_amount, 2) . ". Thank you for your reservation.";
                } else {
                    // Message for approval
                    $message = "Hello " . strtoupper($customer_name) . ", your order " . strtoupper($product_name) . " has been APPROVED! Total amount: PHP " . number_format($total_amount, 2) . ". We will notify you once it's ready for pickup.";
                }

                // Send SMS notification using Telerivet
                try {
                    // Initialize the Telerivet API
                    $telerivet = new Telerivet_API($api_key);

                    // Get a reference to the project
                    $project = $telerivet->initProjectById($project_id);

                    // Send the message
                    $sent_msg = $project->sendMessage(array(
                        'to_number' => $contact_number,
                        'content' => $message
                    ));

                    error_log("SMS sent to $customer_name. Message ID: " . $sent_msg->id);
                } catch (Exception $e) {
                    error_log("SMS failed: " . $e->getMessage());
                }
            }
            mysqli_stmt_close($stmt_res);
        }

        // Update reservation status
        $update_query = "UPDATE reservations SET STATUS = ? WHERE RESERVATION_ID = ? AND SHOP_ID = ?";
        $stmt = mysqli_prepare($db, $update_query);
        mysqli_stmt_bind_param($stmt, "sii", $new_status, $reservation_id, $shop_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: reservations.php");
        exit();
    }
}

// Fetch reservations
function fetchReservations($db, $shop_id, $status_condition)
{
    $query = "SELECT * FROM reservations WHERE SHOP_ID = ? AND STATUS IN ($status_condition) ORDER BY TIMESTAMP DESC";
    if ($stmt = mysqli_prepare($db, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $shop_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }
    return false;
}

$pending_approved_reservations = fetchReservations($db, $shop_id, "'Pending', 'Approved'");
$pickup_reservations = fetchReservations($db, $shop_id, "'For Pick Up'");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reservations</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>

<body>
    <?php include '../include/topbar.php'; ?>

    <div class="max-w-7xl mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold text-white-800">Reservations</h1>

        <?php function renderTable($title, $result, $isPickup = false)
        { ?>
            <div class="bg-white mt-6 p-6 rounded-lg shadow-md overflow-x-auto text-gray-800">
                <h2 class="text-xl font-semibold mb-4"><?php echo $title; ?></h2>
                <table class="w-full border-collapse">
                    <thead>
                        <tr>
                            <th class="p-3 text-left">Customer Name</th>
                            <th class="p-3 text-left">Contact Number</th>
                            <th class="p-3 text-left">Product Code</th>
                            <th class="p-3 text-left">Quantity</th>
                            <th class="p-3 text-left">Pickup Date</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        <?php if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tr>
                                    <td class='p-3'><?= htmlspecialchars($row['CUSTOMER_NAME']) ?></td>
                                    <td class='p-3'><?= htmlspecialchars($row['CONTACT_NUMBER']) ?></td>
                                    <td class='p-3'><?= htmlspecialchars($row['PRODUCT_CODE']) ?></td>
                                    <td class='p-3'><?= $row['QUANTITY'] ?></td>
                                    <td class='p-3'><?= date('Y-m-d', strtotime($row['PICKUP_DATE'])) ?></td>
                                    <td class='p-3'><?= htmlspecialchars($row['STATUS']) ?></td>
                                    <td class='p-3 flex justify-center space-x-4'>
                                        <?php if (!$isPickup && $row['STATUS'] == 'Pending') { ?>
                                            <a href='?action=approve&id=<?= $row['RESERVATION_ID'] ?>'
                                                class='text-green-600 hover:text-green-800 text-3xl' title='Approve'><i
                                                    class='ph ph-check'></i></a>
                                            <a href='?action=reject&id=<?= $row['RESERVATION_ID'] ?>'
                                                class='text-red-600 hover:text-red-800 text-3xl' title='Reject'><i
                                                    class='ph ph-x'></i></a>
                                        <?php } elseif (!$isPickup && $row['STATUS'] == 'Approved') { ?>
                                            <a href='?action=for_pickup&id=<?= $row['RESERVATION_ID'] ?>'
                                                class='text-yellow-600 hover:text-yellow-800 text-3xl' title='Move to For Pick Up'><i
                                                    class='ph ph-truck'></i></a>
                                        <?php } elseif ($isPickup) { ?>
                                            <a href='?action=picked_up&id=<?= $row['RESERVATION_ID'] ?>'
                                                class='text-blue-600 hover:text-blue-800 text-3xl' title='Mark as Picked Up'><i
                                                    class='ph ph-check-circle'></i></a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php }
                        } else {
                            echo "<tr><td colspan='7' class='text-center p-4 text-xl font-semibold text-red-600'>No Reservations Found!</td></tr>";
                        } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>

        <?php renderTable("Pending & Approved Reservations", $pending_approved_reservations); ?>
        <?php renderTable("For Pick Up", $pickup_reservations, true); ?>
    </div>
</body>

</html>